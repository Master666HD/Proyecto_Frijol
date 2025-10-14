package com.example.appfrijol.presentation.login

import android.util.Log
import androidx.compose.runtime.getValue
import androidx.compose.runtime.mutableStateOf
import androidx.compose.runtime.setValue
import androidx.lifecycle.ViewModel
import androidx.lifecycle.viewModelScope
import com.example.appfrijol.data.local.datastore.DataStoreManager
import com.example.appfrijol.data.remote.dto.LoginRequest
import com.example.appfrijol.data.repository.AuthRepository
import com.example.appfrijol.domain.model.UserInfo
import dagger.hilt.android.lifecycle.HiltViewModel
import kotlinx.coroutines.flow.MutableStateFlow
import kotlinx.coroutines.flow.StateFlow
import kotlinx.coroutines.flow.firstOrNull
import kotlinx.coroutines.launch
import javax.inject.Inject
@HiltViewModel
class LoginViewModel @Inject constructor(
    private val authRepository: AuthRepository,
    private val dataStoreManager: DataStoreManager
) : ViewModel() {

    private val _currentUser = MutableStateFlow<UserInfo?>(null)
    val currentUser: StateFlow<UserInfo?> = _currentUser

    var isLoading by mutableStateOf(false)
    var message by mutableStateOf("")

    fun saveUser(user: UserInfo) {
        _currentUser.value = user
    }

    fun login(username: String, password: String, onSuccess: (UserInfo) -> Unit) {
        // 🔹 Validación de campos vacíos antes de iniciar login
        if (username.isBlank() && password.isBlank()) {
            message = "Usuario y contraseña no pueden estar vacíos"
            return
        }
        if (username.isBlank()) {
            message = "Usuario no puede estar vacío"
            return
        }
        if (password.isBlank()) {
            message = "Contraseña no puede estar vacía"
            return
        }

        viewModelScope.launch {
            isLoading = true
            message = ""

            val result = authRepository.login(LoginRequest(username, password))

            result.onSuccess { loginResponse ->
                val userWithToken = loginResponse.user.copy(token = loginResponse.token)

                // Guardar sesión
                dataStoreManager.saveSession(
                    token = loginResponse.token,
                    userName = loginResponse.user.userName ?: loginResponse.user.firstName ?: "",
                    userId = loginResponse.user.id.toString(),
                    email = loginResponse.user.email.toString()
                )

                saveUser(userWithToken)
                onSuccess(userWithToken)
            }

            result.onFailure { e ->
                // 🔹 Mensaje del backend o fallback
                message = e.message ?: "Ocurrió un error inesperado"
            }

            isLoading = false
        }
    }

    fun logout(onLogout: () -> Unit) {
        viewModelScope.launch {
            dataStoreManager.clearSession()
            _currentUser.value = null
            onLogout()
        }
    }

    init {
        loadSession()
    }

    private fun loadSession() {
        viewModelScope.launch {
            val userName = dataStoreManager.userNameFlow.firstOrNull()
            val token = dataStoreManager.tokenFlow.firstOrNull()
            val userId = dataStoreManager.userIdFlow.firstOrNull()
            val email = dataStoreManager.emailFlow.firstOrNull()

            if (!userName.isNullOrEmpty() && !token.isNullOrEmpty()) {
                _currentUser.value = UserInfo(
                    id = userId?.toIntOrNull() ?: 0,
                    userName = userName,
                    token = token,
                    firstName = "",
                    lastName = "",
                    email = email,
                    role = ""
                )
            }
        }
    }
}
