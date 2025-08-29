package com.example.appfrijol.presentation.home

import androidx.lifecycle.ViewModel
import androidx.lifecycle.viewModelScope
import com.example.appfrijol.domain.model.ClassificationSummary
import dagger.hilt.android.lifecycle.HiltViewModel
import kotlinx.coroutines.flow.MutableStateFlow
import kotlinx.coroutines.flow.StateFlow
import kotlinx.coroutines.launch
import javax.inject.Inject

@HiltViewModel
class ClassificationViewModel @Inject constructor(
    private val repository: ClassificationRepository
) : ViewModel() {

    // Datos de resumen
    private val _summaryData = MutableStateFlow<ClassificationSummary?>(null)
    val summaryData: StateFlow<ClassificationSummary?> = _summaryData

    // Datos para gráfico de barras
    private val _barChartData = MutableStateFlow<List<Pair<String, Float>>>(emptyList())
    val barChartData: StateFlow<List<Pair<String, Float>>> = _barChartData

    // Nombre del usuario
    private val _userName = MutableStateFlow("Usuario")
    val userName: StateFlow<String> = _userName

    // Estado de refresco
    private val _isRefreshing = MutableStateFlow(false)
    val isRefreshing: StateFlow<Boolean> = _isRefreshing

    fun setUserName(name: String) {
        _userName.value = name
    }

    fun fetchData(userId: String) {
        viewModelScope.launch {
            _isRefreshing.value = true
            try {
                val apiResponse = repository.getClassificationSummary(userId)
                _summaryData.value = apiResponse

                val barEntries = apiResponse.beansByColor.map { (color, count) ->
                    color to count.toFloat()
                }
                _barChartData.value = barEntries
            } catch (e: Exception) {
                e.printStackTrace()
            } finally {
                _isRefreshing.value = false
            }
        }
    }

    fun refreshData(userId: String) {
        fetchData(userId)
    }
}
