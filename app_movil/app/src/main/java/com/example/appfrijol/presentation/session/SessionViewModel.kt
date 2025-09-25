package com.example.appfrijol.presentation.session

import androidx.lifecycle.ViewModel
import androidx.lifecycle.viewModelScope
import com.example.appfrijol.data.local.datastore.DataStoreManager
import com.example.appfrijol.data.remote.api.ApiService
import com.example.appfrijol.data.remote.dto.UpdatePasswordRequest
import com.example.appfrijol.data.remote.dto.UpdateUserNameRequest
import dagger.hilt.android.lifecycle.HiltViewModel
import kotlinx.coroutines.flow.SharingStarted
import kotlinx.coroutines.flow.StateFlow
import kotlinx.coroutines.flow.first
import kotlinx.coroutines.flow.firstOrNull
import kotlinx.coroutines.flow.map
import kotlinx.coroutines.flow.stateIn
import kotlinx.coroutines.launch
import javax.inject.Inject

@HiltViewModel
class SessionViewModel @Inject constructor(
    private val dataStoreManager: DataStoreManager,
    private val api: ApiService // 👈 tu retrofit service inyectado
) : ViewModel() {

    val isLoggedIn: StateFlow<Boolean> = dataStoreManager.tokenFlow
        .map { !it.isNullOrEmpty() }
        .stateIn(viewModelScope, SharingStarted.Lazily, false)

    val userName: StateFlow<String> = dataStoreManager.userNameFlow
        .map { it ?: "Usuario" }
        .stateIn(viewModelScope, SharingStarted.Lazily, "Usuario")

    val userId: StateFlow<String> = dataStoreManager.userIdFlow
        .map { it ?: "" }
        .stateIn(viewModelScope, SharingStarted.Lazily, "")

    fun login(token: String, userName: String, userId: Any) {
        viewModelScope.launch {

        }
    }

    fun logout() {
        viewModelScope.launch {
            // Solo borramos el token, manteniendo userName y userId
            dataStoreManager.clearToken()
            println("Sesión cerrada. Token eliminado, userName persistente: ${dataStoreManager.userNameFlow.firstOrNull()}")
        }
    }


    fun updateUserName(newName: String) {
        viewModelScope.launch {
            try {
                // 🚀 gracias al AuthInterceptor ya se manda el Bearer token
                val response = api.updateUserName(UpdateUserNameRequest(newName))
                if (response.isSuccessful) {
                    val updatedUser = response.body()
                    val token = dataStoreManager.tokenFlow.first() ?: ""
                    val id = updatedUser?.id ?: userId.value

                    // actualizamos el DataStore
                    dataStoreManager.saveSession(
                        token = token,
                        userName = updatedUser?.name ?: newName,
                        userId = id
                    )
                } else {
                    // manejar error 4xx / 5xx
                    println("Error API: ${response.errorBody()?.string()}")
                }
            } catch (e: Exception) {
                println("Excepción en updateUserName: $e")
            }
        }
    }
    fun updatePassword(current: String, new: String) {
        viewModelScope.launch {
            try {
                val request = UpdatePasswordRequest(
                    currentPassword = current,
                    newPassword = new,
                    newPassword_confirmation = new
                )

                val response = api.updatePassword(request)
                if (response.isSuccessful) {
                    println("Contraseña actualizada correctamente")
                } else {
                    val error = response.errorBody()?.string()
                    println("Error al actualizar contraseña: $error")
                }
            } catch (e: Exception) {
                println("Excepción en updatePassword: $e")
            }
        }
    }

}
