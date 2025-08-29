package com.example.appfrijol.data.repository

import com.example.appfrijol.data.local.datastore.DataStoreManager
import com.example.appfrijol.data.remote.api.ApiService
import com.example.appfrijol.data.remote.models.LoginRequest
import com.example.appfrijol.data.remote.models.LoginResponse
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
                    // Guardar token + nombre + userId en DataStore
                    dataStoreManager.saveSession(
                        body.token ?: "",
                        body.user.firstName ?: body.user.userName ?: "Usuario",
                        body.user.id?.toString() ?: ""  // ✅ ahora es String
                    )


                    Result.success(body)
                } else {
                    Result.failure(Exception("Respuesta vacía del servidor"))
                }
            } else {
                val errorMsg = response.errorBody()?.string() ?: "Error desconocido"
                Result.failure(Exception(errorMsg))
            }
        } catch (e: Exception) {
            Result.failure(e)
        }
    }

    suspend fun logout() {
        dataStoreManager.clearSession() // 👈 borra token, nombre y userId
    }
}
