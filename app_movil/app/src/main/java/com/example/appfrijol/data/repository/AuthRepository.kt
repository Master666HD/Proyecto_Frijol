package com.example.appfrijol.data.repository

import com.example.appfrijol.data.local.datastore.DataStoreManager
import com.example.appfrijol.data.remote.api.ApiService
import com.example.appfrijol.data.remote.dto.LoginRequest
import com.example.appfrijol.data.remote.dto.LoginResponse
import javax.inject.Inject
class AuthRepository @Inject constructor(
    private val api: ApiService,
    private val dataStoreManager: DataStoreManager
) {
    suspend fun login(request: LoginRequest): Result<LoginResponse> {
        return try {
            val response = api.login(request)

            if (response.isSuccessful) {
                val body = response.body()
                if (body != null) {
                    Result.success(body)
                } else {
                    Result.failure(Exception("Respuesta vacía del servidor"))
                }
            } else {
                // Aquí interpretamos el código HTTP
                val errorMsg = when (response.code()) {
                    401 -> "Usuario o contraseña incorrectos. Intente de nuevo."
                    403 -> "Acceso denegado. Rol no permitido."
                    else -> response.errorBody()?.string() ?: "Error desconocido en el servidor."
                }
                Result.failure(Exception(errorMsg))
            }
        } catch (e: Exception) {
            Result.failure(Exception("Error de conexión: ${e.localizedMessage}"))
        }
    }


    suspend fun logout() {
        dataStoreManager.clearSession() // 👈 borra token, nombre y userId
    }
}
