package com.example.appfrijol.data.repository

import com.example.appfrijol.data.remote.api.ApiService
import com.example.appfrijol.data.remote.dto.CompareRequest
import com.example.appfrijol.data.remote.dto.SeedDto
import com.example.appfrijol.domain.model.Batch
import com.example.appfrijol.domain.model.LastBatchSummary
import com.example.appfrijol.domain.model.ProductivityMetrics
import com.example.appfrijol.domain.model.Seed
import javax.inject.Inject

class SeedRepository @Inject constructor(
    private val apiService: ApiService
) {

    suspend fun getLastBatchSummary(): Result<LastBatchSummary> {
        return try {
            val dto = apiService.getLastBatchSummary() // LastBatchSummaryDto
            val domain = LastBatchSummary(
                by_color = dto.by_color,
                by_size = dto.by_size,
                by_weight = dto.by_weight,
                by_status = dto.by_status,
                date = dto.date
            )
            Result.success(domain)
        } catch (e: Exception) {
            Result.failure(e)
        }
    }

    // --------------------------
// Métricas de productividad
// --------------------------
    suspend fun getProductivityMetrics(): Result<ProductivityMetrics> {
        return try {
            val dto = apiService.getProductivityMetrics() // ProductivityMetricsDto
            val domain = ProductivityMetrics(
                total_beans = dto.total_beans,
                batches_last_week = dto.batches_last_week
            )
            Result.success(domain)
        } catch (e: Exception) {
            Result.failure(e)
        }
    }

    // Historial completo
    suspend fun getBatchHistory(): Result<List<Batch>> {
        return try {
            val response = apiService.getBatchHistory()
            if (response.isSuccessful) {
                val domainBatches = response.body()?.map { dto ->
                    Batch(
                        start = dto.start,
                        end = dto.end,
                        total = dto.total,
                        seeds = dto.seeds.map { seedDto ->
                            Seed(
                                id = seedDto.id,
                                color = seedDto.color,
                                size = seedDto.size,
                                weight = seedDto.weight,
                                status = seedDto.status,
                                registration_date = seedDto.registration_date
                            )
                        }
                    )
                } ?: emptyList()

                Result.success(domainBatches)
            } else {
                Result.failure(Exception("Error ${response.code()}"))
            }
        } catch (e: Exception) {
            Result.failure(e)
        }
    }



    // Detalle de un lote


    // Comparar lotes
    suspend fun compareBatches(ids: List<Int>): Result<List<Seed>> {
        return try {
            val response = apiService.compareBatches(CompareRequest(ids))
            if (response.isSuccessful) {
                val dtos = response.body() ?: emptyList<SeedDto>()

                // Mapear DTOs a modelos de dominio
                val seeds = dtos.map { dto ->
                    Seed(
                        id = dto.id,
                        color = dto.color,
                        size = dto.size,
                        weight = dto.weight,
                        status = dto.status,
                        registration_date = dto.registration_date
                    )
                }

                Result.success(seeds)
            } else {
                Result.failure(Exception("Error ${response.code()}"))
            }
        } catch (e: Exception) {
            Result.failure(e)
        }
    }


    suspend fun exportBatchByRange(start: String, end: String, format: String): Result<ByteArray> {
        return try {
            val response = apiService.exportBatch(format = format, start = start, end = end)
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