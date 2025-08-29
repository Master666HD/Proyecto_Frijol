package com.example.appfrijol.presentation.login

import androidx.compose.runtime.getValue
import androidx.compose.runtime.mutableStateOf
import androidx.compose.runtime.setValue
import androidx.lifecycle.ViewModel
import androidx.lifecycle.viewModelScope
import com.example.appfrijol.data.local.datastore.DataStoreManager
import com.example.appfrijol.data.remote.models.LoginRequest
import com.example.appfrijol.data.repository.AuthRepository
import com.example.appfrijol.domain.model.UserInfo
import dagger.hilt.android.lifecycle.HiltViewModel
import kotlinx.coroutines.flow.MutableStateFlow
import kotlinx.coroutines.flow.StateFlow
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
        if (username.isBlank() || password.isBlank()) {
            message = "Usuario y contraseña requeridos"
            return
        }

        viewModelScope.launch {
            isLoading = true
            message = ""

            val result = authRepository.login(LoginRequest(username, password))

            result.onSuccess { loginResponse ->
                // Crear usuario con token
                val userWithToken = loginResponse.user.copy(token = loginResponse.token)

                // ✅ Guardar en DataStore (token + nombre)
                dataStoreManager.saveSession(
                    token = loginResponse.token,
                    userName = loginResponse.user.firstName ?: loginResponse.user.userName ?: "",
                    userId = loginResponse.user.id.toString() // 🔑 convertir a String
                )


                // Guardar localmente
                saveUser(userWithToken)

                // Notificar éxito
                onSuccess(userWithToken)
            }

            result.onFailure { e ->
                message = e.message ?: "Error desconocido"
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
}

