package com.example.appfrijol.data.repository

import android.util.Log
import com.example.appfrijol.data.remote.api.ApiService
import com.example.appfrijol.data.remote.models.LoginRequest
import com.example.appfrijol.data.remote.models.LoginResponse

class AuthRepository(private val api: ApiService) {

    suspend fun login(request: LoginRequest): Result<LoginResponse> {
        return try {
            val response = api.login(request) // Response<LoginResponse>
            if (response.isSuccessful) {
                val body = response.body()
                if (body != null) {
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
}