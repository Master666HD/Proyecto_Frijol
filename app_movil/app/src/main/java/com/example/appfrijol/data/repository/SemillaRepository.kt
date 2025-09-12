package com.example.appfrijol.data.repository

import com.example.appfrijol.data.remote.api.ApiService
import com.example.appfrijol.data.remote.models.CompararRequest
import com.example.appfrijol.domain.model.Lote
import com.example.appfrijol.domain.model.Semilla
import javax.inject.Inject

class SemillaRepository @Inject constructor(
    private val apiService: ApiService
) {
    // Historial completo
    suspend fun obtenerHistorialLotes(): Result<List<Lote>> {
        return try {
            val response = apiService.obtenerHistorialLotes()
            if (response.isSuccessful) {
                Result.success(response.body() ?: emptyList())
            } else {
                Result.failure(Exception("Error ${response.code()}"))
            }
        } catch (e: Exception) {
            Result.failure(e)
        }
    }



    // Detalle de un lote
    suspend fun obtenerDetalleLote(id: Int): Result<Semilla> {
        return try {
            val response = apiService.obtenerDetalleLote(id)
            if (response.isSuccessful) {
                response.body()?.let { Result.success(it) }
                    ?: Result.failure(Exception("Lote vacío"))
            } else {
                Result.failure(Exception("Error ${response.code()}"))
            }
        } catch (e: Exception) {
            Result.failure(e)
        }
    }

    // Comparar lotes
    suspend fun compararLotes(ids: List<Int>): Result<List<Semilla>> {
        return try {
            val response = apiService.compararLotes(CompararRequest(ids))
            if (response.isSuccessful) {
                Result.success(response.body() ?: emptyList())
            } else {
                Result.failure(Exception("Error ${response.code()}"))
            }
        } catch (e: Exception) {
            Result.failure(e)
        }
    }

    suspend fun exportarLotePorRango(inicio: String, fin: String, formato: String): Result<ByteArray> {
        return try {
            val response = apiService.exportarLote(formato = formato, inicio = inicio, fin = fin)
            if (response.isSuccessful) {
                val bytes = response.body()?.bytes() ?: ByteArray(0)
                Result.success(bytes)
            } else {
                Result.failure(Exception("Error ${response.code()}"))
            }
        } catch (e: Exception) {
            Result.failure(e)
        }
    }




}