package com.example.appfrijol.data.remote.api

import com.example.appfrijol.data.remote.models.ApiResponse
import com.example.appfrijol.data.remote.models.ClassificationSummaryResponse
import com.example.appfrijol.data.remote.models.LoginRequest
import com.example.appfrijol.data.remote.models.LoginResponse
import com.example.appfrijol.domain.model.User
import retrofit2.Response
import retrofit2.http.Body
import retrofit2.http.GET
import retrofit2.http.Headers
import retrofit2.http.POST
import retrofit2.http.Query

interface ApiService {
    @Headers("Accept: application/json")

    @POST("register")
    suspend fun register(@Body usuario: User): Response<ApiResponse>


    @POST("login")
    suspend fun login(@Body request: LoginRequest): Response<LoginResponse>


    @GET("classification/summary")
    suspend fun getClassificationSummary(
        @Query("idUsuario") idUsuario: String  // <-- cambiar userId a idUsuario
    ): ClassificationSummaryResponse
}