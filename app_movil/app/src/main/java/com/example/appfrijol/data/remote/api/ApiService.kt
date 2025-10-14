package com.example.appfrijol.data.remote.api


import com.example.appfrijol.data.remote.dto.ApiResponse
import com.example.appfrijol.data.remote.dto.BatchDto
import com.example.appfrijol.data.remote.dto.CompareRequest
import com.example.appfrijol.data.remote.dto.LastBatchSummaryDto
import com.example.appfrijol.data.remote.dto.LoginRequest
import com.example.appfrijol.data.remote.dto.LoginResponse
import com.example.appfrijol.data.remote.dto.ProductivityMetricsDto
import com.example.appfrijol.data.remote.dto.SeedDto
import com.example.appfrijol.data.remote.dto.UpdatePasswordRequest
import com.example.appfrijol.data.remote.dto.UpdateUserNameRequest
import com.example.appfrijol.data.remote.dto.UserResponse
import com.example.appfrijol.domain.model.User
import okhttp3.ResponseBody
import retrofit2.Response
import retrofit2.http.Body
import retrofit2.http.GET
import retrofit2.http.Header
import retrofit2.http.Headers
import retrofit2.http.POST
import retrofit2.http.PUT
import retrofit2.http.Path
import retrofit2.http.Query
import retrofit2.http.Streaming

interface ApiService {
    @Headers("Accept: application/json")

    @POST("register")
    suspend fun register(@Body usuario: User): Response<ApiResponse>


    @POST("login")
    suspend fun login(@Body request: LoginRequest): Response<LoginResponse>

    @PUT("user")
    suspend fun updateUserName(
        @Body request: UpdateUserNameRequest
    ): Response<UserResponse>




    @PUT("user/password")
    suspend fun updatePassword(
        @Body request: UpdatePasswordRequest
    ): Response<Void> // solo devuelve mensaje



    @GET("last-batch-summary")
    suspend fun getLastBatchSummary(): LastBatchSummaryDto

    @GET("productivity-metrics")
    suspend fun getProductivityMetrics(): ProductivityMetricsDto

    // Historial de lotes
    @GET("seeds")
    suspend fun getBatchHistory(): Response<List<BatchDto>>

    // Get batch detail


    // Compare multiple batches
    @POST("seeds/compare")
    suspend fun compareBatches(
        @Body request: CompareRequest
    ): Response<List<SeedDto>>

    // Export batch (CSV or PDF)
    @GET("seeds/export/{format}")
    suspend fun exportBatch(
        @Path("format") format: String,      // "csv" or "pdf"
        @Query("start") start: String,       // start date
        @Query("end") end: String            // end date
    ): Response<ResponseBody>


    @GET("batches/pdf")
    @Streaming
    suspend fun downloadBatchesPdf(
        @Header("Authorization") token: String
    ): Response<ResponseBody>

}