package com.example.appfrijol.presentation.register

import android.content.Context
import androidx.compose.runtime.mutableStateOf
import androidx.datastore.preferences.preferencesDataStore
import androidx.lifecycle.ViewModel
import androidx.lifecycle.viewModelScope
import com.example.appfrijol.data.remote.api.ApiService
import com.example.appfrijol.data.remote.dto.ApiResponse
import com.example.appfrijol.domain.model.User
import com.google.gson.Gson
import com.google.gson.JsonObject
import dagger.hilt.android.lifecycle.HiltViewModel
import kotlinx.coroutines.launch
import retrofit2.Response
import javax.inject.Inject
import androidx.compose.runtime.State

@HiltViewModel
class RegisterViewModel @Inject constructor(
    private val apiService: ApiService
) : ViewModel() {

    private val _uiState = mutableStateOf(RegisterUiState())
    val uiState: State<RegisterUiState> = _uiState

    fun registerUser(
        firstName: String,
        lastName: String,
        phoneNumber: String,
        email: String,
        userName: String,
        password: String,
        onSuccess: () -> Unit
    ) {
        _uiState.value = _uiState.value.copy(isLoading = true, message = "", errors = emptyMap())

        viewModelScope.launch {
            val newUser = User(
                firstName = firstName,
                lastName = lastName,
                phoneNumber = phoneNumber,
                email = email,
                userName = userName,
                password = password,
                role = "Agricultor"
            )

            try {
                val response = apiService.register(newUser)
                _uiState.value = _uiState.value.copy(isLoading = false)

                if (response.isSuccessful) {
                    _uiState.value = _uiState.value.copy(
                        message = "Registro exitoso",
                        errors = emptyMap()
                    )
                    onSuccess()
                } else {
                    handleErrorResponse(response)
                }
            } catch (e: Exception) {
                _uiState.value = _uiState.value.copy(
                    isLoading = false,
                    message = "Error de conexión: ${e.message}"
                )
            }
        }
    }


    private fun handleErrorResponse(response: Response<ApiResponse>) {
        try {
            val errorBody = response.errorBody()?.string()
            val gson = Gson()
            val errorResponse = gson.fromJson(errorBody, JsonObject::class.java)

            if (errorResponse.has("errors")) {
                val errorsJson = errorResponse.getAsJsonObject("errors")
                val errorsMap = mutableMapOf<String, String>()

                errorsJson.entrySet().forEach { entry ->
                    val errorMessages = entry.value.asJsonArray
                    if (errorMessages.size() > 0) {
                        errorsMap[entry.key] = errorMessages[0].asString
                    }
                }
                _uiState.value = _uiState.value.copy(errors = errorsMap)
            } else if (errorResponse.has("message")) {
                _uiState.value = _uiState.value.copy(
                    message = errorResponse.get("message").asString
                )
            }
        } catch (e: Exception) {
            _uiState.value = _uiState.value.copy(
                message = "Error al procesar la respuesta del servidor"
            )
        }
    }
    fun onFieldChange(field: String, value: String) {
        _uiState.value = when (field) {
            "firstName" -> _uiState.value.copy(
                firstName = value,
                errors = _uiState.value.errors.toMutableMap().apply { remove("firstName") }
            )
            "lastName" -> _uiState.value.copy(
                lastName = value,
                errors = _uiState.value.errors.toMutableMap().apply { remove("lastName") }
            )
            "phoneNumber" -> _uiState.value.copy(
                phoneNumber = value,
                errors = _uiState.value.errors.toMutableMap().apply { remove("phoneNumber") }
            )
            "email" -> _uiState.value.copy(
                email = value,
                errors = _uiState.value.errors.toMutableMap().apply { remove("email") }
            )
            "userName" -> _uiState.value.copy(
                userName = value,
                errors = _uiState.value.errors.toMutableMap().apply { remove("userName") }
            )
            "password" -> _uiState.value.copy(
                password = value,
                errors = _uiState.value.errors.toMutableMap().apply { remove("password") }
            )
            else -> _uiState.value
        }
    }

    fun clearState() {
        _uiState.value = RegisterUiState()
    }
}


val Context.dataStore by preferencesDataStore(name = "auth_prefs")
