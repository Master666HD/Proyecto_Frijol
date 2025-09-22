package com.example.appfrijol.presentation.home

import androidx.compose.runtime.getValue
import androidx.compose.runtime.mutableStateOf
import androidx.compose.runtime.setValue
import androidx.lifecycle.ViewModel
import androidx.lifecycle.viewModelScope
import com.example.appfrijol.data.repository.SeedRepository
import com.example.appfrijol.domain.model.LastBatchSummary
import com.example.appfrijol.domain.model.ProductivityMetrics
import dagger.hilt.android.lifecycle.HiltViewModel
import kotlinx.coroutines.launch
import javax.inject.Inject

@HiltViewModel
class HomeViewModel @Inject constructor(
    private val repository: SeedRepository
) : ViewModel() {

    var lastBatchSummary by mutableStateOf<LastBatchSummary?>(null)
        private set

    var productivityMetrics by mutableStateOf<ProductivityMetrics?>(null)
        private set

    var errorMessage by mutableStateOf<String?>(null)
        private set

    fun loadData() {
        viewModelScope.launch {
            // Cargar último lote
            repository.getLastBatchSummary().onSuccess { summary ->
                lastBatchSummary = summary
            }.onFailure { e ->
                errorMessage = e.message
            }

            // Cargar métricas de productividad
            repository.getProductivityMetrics().onSuccess { metrics ->
                productivityMetrics = metrics
            }.onFailure { e ->
                errorMessage = e.message
            }
        }
    }
}


