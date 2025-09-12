package com.example.appfrijol.presentation.result

import android.content.Context
import android.content.Intent
import android.widget.Toast
import androidx.compose.runtime.mutableStateListOf
import androidx.core.content.FileProvider
import androidx.lifecycle.ViewModel
import androidx.lifecycle.viewModelScope
import com.example.appfrijol.data.repository.SemillaRepository
import com.example.appfrijol.domain.model.Lote
import com.example.appfrijol.domain.model.Semilla
import dagger.hilt.android.lifecycle.HiltViewModel
import kotlinx.coroutines.flow.MutableStateFlow
import kotlinx.coroutines.flow.StateFlow
import kotlinx.coroutines.launch
import java.io.File
import javax.inject.Inject

@HiltViewModel
class SemillaViewModel @Inject constructor(
    private val repository: SemillaRepository
) : ViewModel() {

    // Historial completo de semillas
    private val _historial = MutableStateFlow<List<Lote>>(emptyList())
    val historial: StateFlow<List<Lote>> = _historial


    // Detalle de lote seleccionado (puede ser útil si quieres mostrar un resumen)
    private val _detalleLote = MutableStateFlow<Semilla?>(null)
    val detalleLote: StateFlow<Semilla?> = _detalleLote

    // Resultado de comparación de varios lotes
    private val _comparacion = MutableStateFlow<List<Semilla>>(emptyList())
    val comparacion: StateFlow<List<Semilla>> = _comparacion

    // Error general
    private val _error = MutableStateFlow<String?>(null)
    val error: StateFlow<String?> = _error




    // --------------------------
    // Funciones
    // --------------------------
    val selectedIds = mutableStateListOf<Int>()
    fun cargarHistorial() {
        viewModelScope.launch {
            val result = repository.obtenerHistorialLotes()
            result.onSuccess { _historial.value = it }
                .onFailure { _error.value = it.message }
        }
    }

    fun cargarDetalleLote(id: Int) {
        viewModelScope.launch {
            val result = repository.obtenerDetalleLote(id)
            result.onSuccess { _detalleLote.value = it }
                .onFailure { _error.value = it.message }
        }
    }

    fun compararLotes(ids: List<Int>) {
        if (ids.size < 2) {
            _error.value = "Selecciona al menos 2 lotes para comparar"
            return
        }

        viewModelScope.launch {
            val result = repository.compararLotes(ids)
            result.onSuccess { _comparacion.value = it }
                .onFailure { _error.value = it.message }
        }
    }

    suspend fun exportarLote(lote: List<Semilla>, formato: String): Result<ByteArray> {
        if (lote.isEmpty()) return Result.failure(Exception("El lote está vacío"))

        val inicio = lote.first().fechaRegistro
        val fin = lote.last().fechaRegistro

        return repository.exportarLotePorRango(inicio, fin, formato)
    }


    fun guardarYAbrirArchivo(context: Context, data: ByteArray, nombre: String, formato: String) {
        try {
            val file = File(context.cacheDir, nombre)
            file.writeBytes(data)

            val uri = FileProvider.getUriForFile(
                context,
                "${context.packageName}.provider",
                file
            )

            val mime = when (formato.lowercase()) {
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



    fun limpiarEstados() {
        _detalleLote.value = null
        _comparacion.value = emptyList()
        _error.value = null
    }
}
