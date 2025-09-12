package com.example.appfrijol.data.repository

import com.example.appfrijol.data.local.datastore.DataStoreManager
import kotlinx.coroutines.flow.firstOrNull
import kotlinx.coroutines.runBlocking
import okhttp3.Interceptor
import okhttp3.Response

class AuthInterceptor(
    private val dataStoreManager: DataStoreManager
) : Interceptor {

    override fun intercept(chain: Interceptor.Chain): Response {
        // Usamos runBlocking **solo aquí**, ya que Interceptor no permite suspend
        val token = runBlocking { dataStoreManager.tokenFlow.firstOrNull() }

        val request = chain.request().newBuilder()
            .apply {
                token?.let {
                    addHeader("Authorization", "Bearer $it")
                }
            }
            .build()

        return chain.proceed(request)
    }
}
