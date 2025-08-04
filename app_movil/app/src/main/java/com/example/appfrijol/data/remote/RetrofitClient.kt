package com.example.appfrijol.data.remote

import com.example.appfrijol.data.remote.api.ApiService
import okhttp3.OkHttpClient
import okhttp3.logging.HttpLoggingInterceptor
import retrofit2.Retrofit
import retrofit2.converter.gson.GsonConverterFactory

object RetrofitClient {
        private const val BASE_URL = "http://192.168.100.155:8000/api/" // Asegúrate de que esta IP sea accesible desde tu emulador/dispositivo

        val apiService: ApiService by lazy {
            // 1. Crear el interceptor de logging
            val loggingInterceptor = HttpLoggingInterceptor().apply { // CORREGIDO AQUÍ
                level = HttpLoggingInterceptor.Level.BODY
            }

            // 2. Crear un cliente OkHttpClient y añadir el interceptor
            val okHttpClient = OkHttpClient.Builder()
                .addInterceptor(loggingInterceptor)
                .build()

            // 3. Construir Retrofit usando el cliente OkHttpClient configurado
            Retrofit.Builder()
                .baseUrl(BASE_URL)
                .addConverterFactory(GsonConverterFactory.create())
                .client(okHttpClient) // ¡Añadimos nuestro cliente con el interceptor aquí!
                .build()
                .create(ApiService::class.java)
        }
    }

