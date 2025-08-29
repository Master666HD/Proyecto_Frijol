package com.example.appfrijol.presentation.home

import androidx.compose.foundation.background
import androidx.compose.foundation.layout.Arrangement
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.fillMaxSize
import androidx.compose.foundation.layout.fillMaxWidth
import androidx.compose.foundation.layout.padding
import androidx.compose.foundation.layout.wrapContentHeight
import androidx.compose.foundation.rememberScrollState
import androidx.compose.foundation.shape.RoundedCornerShape
import androidx.compose.foundation.verticalScroll
import androidx.compose.material3.Card
import androidx.compose.material3.CardDefaults
import androidx.compose.material3.ExperimentalMaterial3Api
import androidx.compose.material3.MaterialTheme
import androidx.compose.material3.Text
import androidx.compose.material3.pulltorefresh.PullToRefreshBox
import androidx.compose.material3.pulltorefresh.rememberPullToRefreshState
import androidx.compose.runtime.Composable
import androidx.compose.runtime.LaunchedEffect
import androidx.compose.runtime.collectAsState
import androidx.compose.runtime.getValue
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.graphics.Brush
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.unit.dp

@OptIn(ExperimentalMaterial3Api::class)
@Composable
fun HomeScreen(
    viewModel: ClassificationViewModel,
    userId: String,
    userName: String
) {
    val scrollState = rememberScrollState()

    // Observa los StateFlows del ViewModel
    val isRefreshing by viewModel.isRefreshing.collectAsState()
    val summaryData by viewModel.summaryData.collectAsState()
    val barChartData by viewModel.barChartData.collectAsState()

    // Estado de pull-to-refresh en Material3
    val pullRefreshState = rememberPullToRefreshState()

    // Llama a fetchData al iniciar el Composable
    LaunchedEffect(userId) {
        viewModel.fetchData(userId)
    }

    PullToRefreshBox(
        modifier = Modifier
            .fillMaxSize()
            .background(
                Brush.verticalGradient(
                    colors = listOf(Color(0xFF4CAF50), Color(0xFF81C784))
                )
            ),
        state = pullRefreshState,
        isRefreshing = isRefreshing,
        onRefresh = { viewModel.refreshData(userId) }
    ) {
        Column(
            modifier = Modifier
                .fillMaxSize()
                .verticalScroll(scrollState)
                .padding(24.dp),
            horizontalAlignment = Alignment.CenterHorizontally,
            verticalArrangement = Arrangement.spacedBy(16.dp)
        ) {
            Card(
                modifier = Modifier
                    .fillMaxWidth()
                    .wrapContentHeight(),
                shape = RoundedCornerShape(16.dp),
                elevation = CardDefaults.cardElevation(defaultElevation = 8.dp)
            ) {
                Column(
                    modifier = Modifier
                        .padding(24.dp)
                        .fillMaxWidth(),
                    horizontalAlignment = Alignment.CenterHorizontally,
                    verticalArrangement = Arrangement.spacedBy(16.dp)
                ) {
                    Text(
                        text = "¡Bienvenido, $userName!",
                        style = MaterialTheme.typography.headlineMedium
                    )

                    // Tu Composable de gráficos
                    ClassificationCharts(
                        viewModel = viewModel,
                        userId = userId // Pasa el userId real obtenido de SessionViewModel
                    )

                }
            }
        }
    }
}
