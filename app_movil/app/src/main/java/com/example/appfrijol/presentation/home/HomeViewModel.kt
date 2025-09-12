package com.example.appfrijol.presentation.home

import androidx.compose.runtime.getValue
import androidx.compose.runtime.mutableStateOf
import androidx.compose.runtime.setValue
import androidx.lifecycle.ViewModel
import androidx.lifecycle.viewModelScope
import com.example.appfrijol.data.remote.api.ApiService
import com.example.appfrijol.data.remote.models.LastBatchSummaryResponse
import com.example.appfrijol.data.remote.models.ProductivityMetricsResponse
import dagger.hilt.android.lifecycle.HiltViewModel
import kotlinx.coroutines.launch
import javax.inject.Inject

@HiltViewModel
class HomeViewModel @Inject constructor(
    private val apiService: ApiService
) : ViewModel() {

    var lastBatchSummary by mutableStateOf<LastBatchSummaryResponse?>(null)
        private set

    var productivityMetrics by mutableStateOf<ProductivityMetricsResponse?>(null)
        private set

    fun loadData() {
        viewModelScope.launch {
            try {
                lastBatchSummary = apiService.getLastBatchSummary()
                productivityMetrics = apiService.getProductivityMetrics()
            } catch (e: Exception) {
                e.printStackTrace()
            }
        }
    }
}

