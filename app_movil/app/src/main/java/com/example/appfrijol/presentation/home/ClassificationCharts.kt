package com.example.appfrijol.presentation.home

import androidx.compose.foundation.layout.Arrangement
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.Spacer
import androidx.compose.foundation.layout.fillMaxWidth
import androidx.compose.foundation.layout.height
import androidx.compose.foundation.layout.padding
import androidx.compose.foundation.layout.size
import androidx.compose.material3.CircularProgressIndicator
import androidx.compose.material3.MaterialTheme
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.runtime.LaunchedEffect
import androidx.compose.runtime.collectAsState
import androidx.compose.runtime.getValue
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.unit.dp
import io.github.dautovicharis.charts.BarChart
import io.github.dautovicharis.charts.PieChart
import io.github.dautovicharis.charts.model.toChartDataSet
import io.github.dautovicharis.charts.style.BarChartDefaults
import io.github.dautovicharis.charts.style.PieChartDefaults
@Composable
fun ClassificationCharts(viewModel: ClassificationViewModel, userId: String) {
    // Lanza la carga de datos solo una vez
    LaunchedEffect(userId) {
        viewModel.fetchData(userId) // Usa el id real que pases
    }

    // Observa summaryData desde StateFlow
    val summary by viewModel.summaryData.collectAsState(initial = null)

    Column(
        modifier = Modifier
            .fillMaxWidth()
            .padding(16.dp),
        horizontalAlignment = Alignment.CenterHorizontally,
        verticalArrangement = Arrangement.spacedBy(16.dp)
    ) {
        Text(
            text = "Resumen de Clasificación",
            style = MaterialTheme.typography.headlineMedium,
            fontWeight = FontWeight.Bold,
            modifier = Modifier.padding(bottom = 8.dp)
        )

        // Mientras summary sea null, mostrar cargando
        summary?.let { data ->

            // Información general
            Column(modifier = Modifier.fillMaxWidth()) {
                Text("Total de Frijoles: ${data.totalBeansClassified}")
                Text("Porcentaje Aptos: ${data.aptPercentage}%")
                Text("Última Clasificación: ${data.lastClassificationDate ?: "N/A"}")
            }

            Spacer(modifier = Modifier.height(16.dp))

            // Pie Chart: APTO vs NO APTO
            ChartCard(title = "Clasificación por Estado") {
                val apto = data.beansByStatus["APTO"]?.toFloat() ?: 0f
                val noApto = data.beansByStatus["NO APTO"]?.toFloat() ?: 0f

                val pieDataSet = listOf(apto, noApto)
                    .toChartDataSet(title = "Estado", postfix = "")

                PieChart(
                    dataSet = pieDataSet,
                    style = PieChartDefaults.style()
                )
            }

            Spacer(modifier = Modifier.height(16.dp))

            // Bar Chart: Por colores
            ChartCard(title = "Clasificación por Color") {
                val colorValues = data.beansByColor.values.map { it.toFloat() }
                val barLabels = data.beansByColor.keys.toList()

                val barDataSet = colorValues
                    .toChartDataSet(
                        title = "Colores",
                        prefix = "",
                        labels = barLabels
                    )

                BarChart(
                    dataSet = barDataSet,
                    style = BarChartDefaults.style()
                )
            }

            Spacer(modifier = Modifier.height(16.dp))

            // Detalle por estado (opcional)
            Column(modifier = Modifier.fillMaxWidth()) {
                Text("Detalle por Estado:", fontWeight = FontWeight.Bold)
                data.beansByStatus.forEach { (estado, cantidad) ->
                    Text("$estado: $cantidad")
                }
            }

            Spacer(modifier = Modifier.height(8.dp))

            // Detalle por color (opcional)
            Column(modifier = Modifier.fillMaxWidth()) {
                Text("Detalle por Color:", fontWeight = FontWeight.Bold)
                data.beansByColor.forEach { (color, cantidad) ->
                    Text("$color: $cantidad")
                }
            }

        } ?: run {
            // Mostrar indicador de carga mientras no hay datos
            CircularProgressIndicator(modifier = Modifier.size(50.dp))
        }
    }
}
