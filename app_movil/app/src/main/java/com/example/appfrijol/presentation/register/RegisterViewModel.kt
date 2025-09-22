package com.example.appfrijol.presentation.register

import android.content.Context
import androidx.datastore.preferences.preferencesDataStore
import androidx.lifecycle.ViewModel
import androidx.lifecycle.viewModelScope
import com.example.appfrijol.data.remote.api.ApiService
import com.example.appfrijol.data.remote.dto.ApiResponse
import com.example.appfrijol.domain.model.User
import com.google.gson.Gson
import com.google.gson.JsonObject
import dagger.hilt.android.lifecycle.HiltViewModel
import kotlinx.coroutines.flow.MutableStateFlow
import kotlinx.coroutines.flow.StateFlow
import kotlinx.coroutines.flow.asStateFlow
import kotlinx.coroutines.launch
import retrofit2.Response
import javax.inject.Inject

@HiltViewModel
class RegisterViewModel @Inject constructor(
    private val apiService: ApiService
) : ViewModel() {

    private val _uiState = MutableStateFlow(RegisterUiState())
    val uiState: StateFlow<RegisterUiState> = _uiState.asStateFlow()

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

    fun clearState() {
        _uiState.value = RegisterUiState()
    }
}


val Context.dataStore by preferencesDataStore(name = "auth_prefs")
