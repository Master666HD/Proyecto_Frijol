package com.example.appfrijol.presentation.result

import android.content.Context
import android.content.Intent
import android.widget.Toast
import androidx.compose.runtime.mutableStateListOf
import androidx.core.content.FileProvider
import androidx.lifecycle.ViewModel
import androidx.lifecycle.viewModelScope
import com.example.appfrijol.data.repository.SeedRepository
import com.example.appfrijol.domain.model.Batch
import com.example.appfrijol.domain.model.Seed
import dagger.hilt.android.lifecycle.HiltViewModel
import kotlinx.coroutines.flow.MutableStateFlow
import kotlinx.coroutines.flow.StateFlow
import kotlinx.coroutines.launch
import java.io.File
import javax.inject.Inject

@HiltViewModel
class SeedViewModel @Inject constructor(
    private val repository: SeedRepository
) : ViewModel() {

    // --------------------------
    // State
    // --------------------------
    private val _history = MutableStateFlow<List<Batch>>(emptyList())
    val history: StateFlow<List<Batch>> = _history

    private val _comparison = MutableStateFlow<List<Seed>>(emptyList())
    val comparison: StateFlow<List<Seed>> = _comparison

    private val _error = MutableStateFlow<String?>(null)
    val error: StateFlow<String?> = _error

    val selectedIds = mutableStateListOf<Int>()


    // --------------------------
    // Functions
    // --------------------------
    fun loadHistory() {
        viewModelScope.launch {
            val result = repository.getBatchHistory()
            result.onSuccess { _history.value = it }
                .onFailure { _error.value = it.message }
        }
    }

    fun compareBatches(ids: List<Int>) {
        if (ids.size < 2) {
            _error.value = "Selecciona al menos 2 lotes para comparar"
            return
        }

        viewModelScope.launch {
            val result = repository.compareBatches(ids)
            result.onSuccess { _comparison.value = it }
                .onFailure { _error.value = it.message }
        }
    }

    suspend fun exportBatch(batch: List<Seed>, format: String): Result<ByteArray> {
        if (batch.isEmpty()) return Result.failure(Exception("El lote está vacío"))

        val start = batch.first().registration_date
        val end = batch.last().registration_date

        return repository.exportBatchByRange(start, end, format)
    }

    fun saveAndOpenFile(context: Context, data: ByteArray, name: String, format: String) {
        try {
            val file = File(context.cacheDir, name)
            file.writeBytes(data)

            val uri = FileProvider.getUriForFile(
                context,
                "${context.packageName}.provider",
                file
            )

            val mime = when (format.lowercase()) {
                "csv" -> "text/csv"
                "pdf" -> "application/pdf"
                else -> "*/*"
            }

            val intent = Intent(Intent.ACTION_VIEW).apply {
                setDataAndType(uri, mime)
                flags = Intent.FLAG_GRANT_READ_URI_PERMISSION
            }
            context.startActivity(intent)
        } catch (e: Exception) {
            Toast.makeText(context, "Error: ${e.message}", Toast.LENGTH_SHORT).show()
        }
    }

    fun clearStates() {
        _comparison.value = emptyList()
        _error.value = null
    }
}

