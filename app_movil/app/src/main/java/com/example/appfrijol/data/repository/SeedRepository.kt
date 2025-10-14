package com.example.appfrijol.data.repository

import android.content.ContentValues
import android.content.Context
import android.content.Intent
import android.net.Uri
import android.os.Environment
import android.provider.MediaStore
import android.util.Log
import android.widget.Toast
import androidx.core.content.FileProvider
import com.example.appfrijol.data.remote.api.ApiService
import com.example.appfrijol.data.remote.dto.CompareRequest
import com.example.appfrijol.data.remote.dto.SeedDto
import com.example.appfrijol.domain.model.Batch
import com.example.appfrijol.domain.model.LastBatchSummary
import com.example.appfrijol.domain.model.ProductivityMetrics
import com.example.appfrijol.domain.model.Seed
import kotlinx.coroutines.Dispatchers
import kotlinx.coroutines.withContext
import java.io.File
import java.io.InputStream
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
            val dto = apiService.getProductivityMetrics()
            Log.d("API_METRICS", "Respuesta cruda: $dto")

            val domain = ProductivityMetrics(
                total_beans = dto.total_beans ?: 0,
                batches_last_week = dto.batches_last_week ?: emptyMap()
            )

            Log.d("API_METRICS", "Convertido a dominio: $domain")

            Result.success(domain)
        } catch (e: Exception) {
            Log.e("API_METRICS", "❌ Error obteniendo métricas", e)
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
    suspend fun downloadPdf(context: Context, token: String): Result<Uri> {
        return try {
            val response = apiService.downloadBatchesPdf("Bearer $token")

            if (response.isSuccessful) {
                response.body()?.let { body ->
                    val filename = "batch_history_${System.currentTimeMillis()}.pdf"
                    val fileUri = savePdfFile(context, body.byteStream(), filename)

                    withContext(Dispatchers.Main) {
                        Toast.makeText(context, "📄 PDF guardado correctamente", Toast.LENGTH_SHORT).show()

                        try {
                            // 🔹 Obtener URI segura para el FileProvider
                            val file = File(fileUri.path!!)
                            val pdfUri = FileProvider.getUriForFile(
                                context,
                                "${context.packageName}.provider",
                                file
                            )

                            // 🔹 Intent para abrir el PDF
                            val intent = Intent(Intent.ACTION_VIEW).apply {
                                setDataAndType(pdfUri, "application/pdf")
                                addFlags(Intent.FLAG_GRANT_READ_URI_PERMISSION)
                                addFlags(Intent.FLAG_ACTIVITY_NEW_TASK)
                            }

                            // 🔹 Verificar si hay app que pueda abrir el PDF
                            if (intent.resolveActivity(context.packageManager) != null) {
                                context.startActivity(intent)
                            } else {
                                Toast.makeText(context, "No hay visor de PDF instalado", Toast.LENGTH_SHORT).show()
                            }

                        } catch (e: Exception) {

                        }
                    }

                    Result.success(fileUri)
                } ?: Result.failure(Exception("Archivo PDF vacío"))
            } else {
                Result.failure(Exception("Error en respuesta: ${response.code()}"))
            }
        } catch (e: Exception) {
            Result.failure(e)
        }
    }



    private fun savePdfFile(context: Context, inputStream: InputStream, filename: String): Uri {
        val contentValues = ContentValues().apply {
            put(MediaStore.Downloads.DISPLAY_NAME, filename)
            put(MediaStore.Downloads.MIME_TYPE, "application/pdf")
            put(MediaStore.Downloads.RELATIVE_PATH, Environment.DIRECTORY_DOWNLOADS)
        }

        val resolver = context.contentResolver
        val uri = resolver.insert(MediaStore.Downloads.EXTERNAL_CONTENT_URI, contentValues)

        uri?.let {
            resolver.openOutputStream(it)?.use { output ->
                inputStream.copyTo(output)
            }
        }
        return uri ?: Uri.EMPTY
    }


}