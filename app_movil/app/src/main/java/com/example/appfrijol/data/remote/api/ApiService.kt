package com.example.appfrijol.data.remote.api

import com.example.appfrijol.data.remote.models.ApiResponse
import com.example.appfrijol.data.remote.models.CompararRequest
import com.example.appfrijol.data.remote.models.LastBatchSummaryResponse
import com.example.appfrijol.data.remote.models.LoginRequest
import com.example.appfrijol.data.remote.models.LoginResponse
import com.example.appfrijol.data.remote.models.ProductivityMetricsResponse
import com.example.appfrijol.domain.model.Lote
import com.example.appfrijol.domain.model.Semilla
import com.example.appfrijol.domain.model.User
import okhttp3.ResponseBody
import retrofit2.Response
import retrofit2.http.Body
import retrofit2.http.GET
import retrofit2.http.Headers
import retrofit2.http.POST
import retrofit2.http.Path
import retrofit2.http.Query

interface ApiService {
    @Headers("Accept: application/json")

    @POST("register")
    suspend fun register(@Body usuario: User): Response<ApiResponse>


    @POST("login")
    suspend fun login(@Body request: LoginRequest): Response<LoginResponse>





    // --- API Service ---
    @GET("last-batch-summary")
    suspend fun getLastBatchSummary(): LastBatchSummaryResponse

    @GET("productivity-metrics")
    suspend fun getProductivityMetrics(): ProductivityMetricsResponse

    // Historial de lotes
    @GET("semillas")
    suspend fun obtenerHistorialLotes(): Response<List<Lote>>



    // Detalle de un lote
    @GET("semillas/{id}")
    suspend fun obtenerDetalleLote(
        @Path("id") id: Int
    ): Response<Semilla>

    // Comparar lotes
    @POST("semillas/comparar")
    suspend fun compararLotes(
        @Body request: CompararRequest
    ): Response<List<Semilla>>

        @GET("semillas/exportar/{formato}")
        suspend fun exportarLote(
            @Path("formato") formato: String,
            @Query("inicio") inicio: String,
            @Query("fin") fin: String,

        ): Response<ResponseBody>




}