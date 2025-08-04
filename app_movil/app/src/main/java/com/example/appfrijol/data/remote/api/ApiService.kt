package com.example.appfrijol.data.remote.api

import com.example.appfrijol.data.remote.models.ApiResponse
import com.example.appfrijol.data.remote.models.LoginRequest
import com.example.appfrijol.data.remote.models.LoginResponse
import com.example.appfrijol.domain.model.User
import retrofit2.Call
import retrofit2.Response
import retrofit2.http.Body
import retrofit2.http.Headers
import retrofit2.http.POST

interface ApiService {
    @Headers("Accept: application/json")
    @POST("register")
    fun register(@Body usuario: User): Call<ApiResponse>

    @POST("login")
    suspend fun login(@Body request: LoginRequest): Response<LoginResponse>
}