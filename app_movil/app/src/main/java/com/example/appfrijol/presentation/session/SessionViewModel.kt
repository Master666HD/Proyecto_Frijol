package com.example.appfrijol.presentation.session

import android.util.Log
import androidx.lifecycle.ViewModel
import androidx.lifecycle.viewModelScope
import com.example.appfrijol.data.local.datastore.DataStoreManager
import com.example.appfrijol.data.remote.api.ApiService
import com.example.appfrijol.data.remote.dto.UpdatePasswordRequest
import com.example.appfrijol.data.remote.dto.UpdateUserNameRequest
import com.example.appfrijol.domain.model.EditNameUiState
import dagger.hilt.android.lifecycle.HiltViewModel
import kotlinx.coroutines.delay
import kotlinx.coroutines.flow.MutableSharedFlow
import kotlinx.coroutines.flow.MutableStateFlow
import kotlinx.coroutines.flow.SharedFlow
import kotlinx.coroutines.flow.SharingStarted
import kotlinx.coroutines.flow.StateFlow
import kotlinx.coroutines.flow.first
import kotlinx.coroutines.flow.firstOrNull
import kotlinx.coroutines.flow.map
import kotlinx.coroutines.flow.stateIn
import kotlinx.coroutines.launch
import org.json.JSONObject
import javax.inject.Inject

@HiltViewModel
class SessionViewModel @Inject constructor(
    private val dataStoreManager: DataStoreManager,
    private val api: ApiService
) : ViewModel() {

    val isLoggedIn: StateFlow<Boolean> = dataStoreManager.tokenFlow
        .map { !it.isNullOrEmpty() }
        .stateIn(viewModelScope, SharingStarted.Lazily, false)
    val email: StateFlow<String> = dataStoreManager.emailFlow
        .map { it ?: "Email" }
        .stateIn(viewModelScope, SharingStarted.Lazily, "Email")

    val userName: StateFlow<String> = dataStoreManager.userNameFlow
        .map { it ?: "Usuario" }
        .stateIn(viewModelScope, SharingStarted.Lazily, "Usuario")

    val userId: StateFlow<String> = dataStoreManager.userIdFlow
        .map { it ?: "" }
        .stateIn(viewModelScope, SharingStarted.Lazily, "")
    val token: StateFlow<String?> = dataStoreManager.tokenFlow
        .stateIn(viewModelScope, SharingStarted.Lazily, null)



    private val _isLocked = MutableStateFlow(false)
    val isLocked: StateFlow<Boolean> = _isLocked

    private val lockDurationMillis = 3 * 60 * 1000L // 3 minutos

    // --- Estados para la contraseña ---
    private val _passwordAttempts = MutableStateFlow(0)
    val passwordAttempts: StateFlow<Int> = _passwordAttempts

    private val _errorMessage = MutableStateFlow<String?>(null)
    val errorMessage: StateFlow<String?> = _errorMessage
    private val _passwordUpdateSuccess = MutableSharedFlow<Unit>()
    val passwordUpdateSuccess: SharedFlow<Unit> = _passwordUpdateSuccess // Evento de un solo uso

    private val _editNameState = MutableStateFlow(EditNameUiState())
    val editNameState: StateFlow<EditNameUiState> = _editNameState
    private val maxAttempts = 4


    fun login(token: String, userName: String, userId: Any) {
        viewModelScope.launch {
            // Implementar login si es necesario
        }
    }

    fun logout() {
        viewModelScope.launch {
            dataStoreManager.clearToken()
            println("Sesión cerrada. Token eliminado, userName persistente: ${dataStoreManager.userNameFlow.firstOrNull()}")
        }
    }

    fun updateUserName(newName: String) {
        viewModelScope.launch {
            _editNameState.value = EditNameUiState(isLoading = true)

            try {
                val response = api.updateUserName(UpdateUserNameRequest(newName))

                if (response.isSuccessful) {
                    val updatedUser = response.body()
                    val token = dataStoreManager.tokenFlow.first() ?: ""
                    val email = dataStoreManager.emailFlow.first() ?: ""
                    val id = updatedUser?.id ?: userId.value

                    // Guardar sesión actualizada
                    dataStoreManager.saveSession(
                        token = token,
                        userName = updatedUser?.userName ?: newName,
                        userId = id,
                        email = email
                    )

                    _editNameState.value = EditNameUiState(success = true)
                } else {
                    // 💡 Capturamos solo el mensaje de error desde JSON
                    val errorJson = response.errorBody()?.string()
                    val message = errorJson?.let {
                        try {
                            JSONObject(it).optString("message", "Error desconocido")
                        } catch (e: Exception) {
                            "Error desconocido"
                        }
                    } ?: "Error desconocido"

                    _editNameState.value = EditNameUiState(errorMessage = message)
                }
            } catch (e: Exception) {
                _editNameState.value = EditNameUiState(errorMessage = "Excepción: ${e.message}")
            }
        }
    }



    fun updatePassword(current: String, new: String) {
        viewModelScope.launch {
            if (_passwordAttempts.value >= maxAttempts) {
                _errorMessage.value = "Has superado el máximo de intentos. Espera 3 minutos."
                lockInputsTemporarily()
                return@launch
            }

            try {
                val request = UpdatePasswordRequest(
                    currentPassword = current,
                    newPassword = new,
                    newPassword_confirmation = new
                )

                val response = api.updatePassword(request)

                if (response.isSuccessful) {
                    // ✅ Éxito → cerrar modal
                    _errorMessage.value = null
                    _passwordAttempts.value = 0
                    _passwordUpdateSuccess.emit(Unit)
                } else {
                    // ❌ Error → no cerrar modal
                    _errorMessage.value = if (response.code() == 403) {
                        "Contraseña actual incorrecta"
                    } else {
                        "Error: ${response.errorBody()?.string() ?: "desconocido"}"
                    }
                    _passwordAttempts.value += 1

                    if (_passwordAttempts.value >= maxAttempts) {
                        _errorMessage.value = "Has superado el máximo de intentos. Espera 3 minutos."
                        lockInputsTemporarily()
                    }
                }
            } catch (e: Exception) {
                _errorMessage.value = "Excepción: ${e.message}"
                _passwordAttempts.value += 1
            }
        }
    }

    private fun lockInputsTemporarily() {
        _isLocked.value = true
        viewModelScope.launch {
            delay(lockDurationMillis)
            _isLocked.value = false
            _passwordAttempts.value = 0
            _errorMessage.value = null
        }
    }

    fun clearError() {
        _errorMessage.value = null
    }

    fun setLocalError(message: String) {
        _errorMessage.value = message
    }
}
