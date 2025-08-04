package com.example.appfrijol.presentation.login

import android.util.Log
import androidx.compose.runtime.getValue
import androidx.compose.runtime.mutableStateOf
import androidx.compose.runtime.setValue
import androidx.lifecycle.ViewModel
import androidx.lifecycle.viewModelScope
import com.example.appfrijol.data.local.datastore.DataStoreManager
import com.example.appfrijol.data.remote.models.LoginRequest
import com.example.appfrijol.data.repository.AuthRepository
import com.example.appfrijol.domain.model.UserInfo
import kotlinx.coroutines.flow.MutableStateFlow
import kotlinx.coroutines.flow.StateFlow
import kotlinx.coroutines.flow.first
import kotlinx.coroutines.launch

class LoginViewModel(private val authRepository: AuthRepository, private val dataStoreManager: DataStoreManager) : ViewModel() {

    private val _usuario = MutableStateFlow<UserInfo?>(null)
    val usuario: StateFlow<UserInfo?> = _usuario

    var cargando by mutableStateOf(false)
    var mensaje by mutableStateOf("")

    fun guardarUsuario(usuario: UserInfo) {
        _usuario.value = usuario
    }

    fun login(usuario: String, contrasenia: String, onSuccess: (UserInfo) -> Unit) {
        if (usuario.isBlank() || contrasenia.isBlank()) {
            mensaje = "Usuario y contraseña son obligatorios"
            return
        }

        viewModelScope.launch {
            cargando = true
            mensaje = ""

            val result = authRepository.login(LoginRequest(usuario, contrasenia))

            if (result.isSuccess) {
                val loginResponse = result.getOrThrow()
                val userWithToken = loginResponse.user.copy(token = loginResponse.token)
                guardarUsuario(userWithToken)
                dataStoreManager.saveToken(loginResponse.token)
                onSuccess(userWithToken)
            } else {
                mensaje = result.exceptionOrNull()?.message ?: "Error desconocido"
            }
        }
    }
}