package com.example.appfrijol.presentation.home

import androidx.compose.animation.AnimatedVisibility
import androidx.compose.animation.core.Animatable
import androidx.compose.animation.core.tween
import androidx.compose.animation.fadeIn
import androidx.compose.animation.slideInVertically
import androidx.compose.foundation.Canvas
import androidx.compose.foundation.ExperimentalFoundationApi
import androidx.compose.foundation.background
import androidx.compose.foundation.layout.Arrangement
import androidx.compose.foundation.layout.Box
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.ColumnScope
import androidx.compose.foundation.layout.Row
import androidx.compose.foundation.layout.Spacer
import androidx.compose.foundation.layout.fillMaxHeight
import androidx.compose.foundation.layout.fillMaxSize
import androidx.compose.foundation.layout.fillMaxWidth
import androidx.compose.foundation.layout.height
import androidx.compose.foundation.layout.padding
import androidx.compose.foundation.layout.size
import androidx.compose.foundation.layout.width
import androidx.compose.foundation.lazy.LazyColumn
import androidx.compose.foundation.shape.CircleShape
import androidx.compose.foundation.shape.RoundedCornerShape
import androidx.compose.material.icons.Icons
import androidx.compose.material.icons.automirrored.outlined.Assignment
import androidx.compose.material.icons.automirrored.outlined.TrendingUp
import androidx.compose.material.icons.outlined.BarChart
import androidx.compose.material.icons.outlined.Scale
import androidx.compose.material3.Card
import androidx.compose.material3.CardDefaults
import androidx.compose.material3.ExperimentalMaterial3Api
import androidx.compose.material3.Icon
import androidx.compose.material3.MaterialTheme
import androidx.compose.material3.Scaffold
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.runtime.LaunchedEffect
import androidx.compose.runtime.remember
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.draw.alpha
import androidx.compose.ui.draw.clip
import androidx.compose.ui.geometry.CornerRadius
import androidx.compose.ui.geometry.Offset
import androidx.compose.ui.geometry.Size
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.graphics.StrokeCap
import androidx.compose.ui.graphics.nativeCanvas
import androidx.compose.ui.graphics.vector.ImageVector
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp
import androidx.hilt.navigation.compose.hiltViewModel


@OptIn(ExperimentalMaterial3Api::class, ExperimentalFoundationApi::class)
@Composable
fun HomeScreen(
    userName: String,
    viewModel: HomeViewModel = hiltViewModel()
) {
    // Cargar datos al iniciar
    LaunchedEffect(Unit) {
        viewModel.loadData()
    }

    val lastBatchSummary = viewModel.lastBatchSummary
    val productivityMetrics = viewModel.productivityMetrics

    // Estado para controlar animaciones
    val animatedVisibility = remember { Animatable(0f) }

    LaunchedEffect(Unit) {
        animatedVisibility.animateTo(1f, animationSpec = tween(800))
    }

    Scaffold() { innerPadding ->
        LazyColumn(
            modifier = Modifier
                .fillMaxSize()
                .padding(innerPadding)
                .background(MaterialTheme.colorScheme.background),
            verticalArrangement = Arrangement.spacedBy(24.dp)
        ) {

            item {
                Card(
                    modifier = Modifier
                        .fillMaxWidth()
                        .padding(horizontal = 16.dp)
                        .alpha(animatedVisibility.value),
                    shape = RoundedCornerShape(16.dp),
                    colors = CardDefaults.cardColors(
                        containerColor = MaterialTheme.colorScheme.surfaceVariant
                    ),
                    elevation = CardDefaults.cardElevation(4.dp)
                ) {
                    Column(
                        modifier = Modifier.padding(20.dp)
                    ) {
                        Text(
                            text = "¡Hola, $userName!",
                            style = MaterialTheme.typography.headlineSmall,
                            fontWeight = FontWeight.Bold,
                            color = MaterialTheme.colorScheme.onSurfaceVariant
                        )
                        Spacer(Modifier.height(4.dp))
                        Text(
                            text = "Bienvenido a tu panel de control",
                            style = MaterialTheme.typography.bodyMedium,
                            color = MaterialTheme.colorScheme.onSurfaceVariant.copy(alpha = 0.7f)
                        )
                    }
                }
            }

            // 📦 Resumen del último lote
            item {
                AnimatedVisibility(
                    visible = animatedVisibility.value > 0.3f,
                    enter = slideInVertically(initialOffsetY = { it / 2 }) + fadeIn(),
                    modifier = Modifier.animateItemPlacement()
                ) {
                    AnalyticsCard(
                        title = "Resumen del Último Lote",
                        icon = Icons.AutoMirrored.Outlined.Assignment,
                        content = {
                            lastBatchSummary?.let { summary ->

                                ImprovedBarChart(
                                    title = "Distribución por Estado",
                                    data = summary.by_status ?: emptyMap(),
                                    barColor = MaterialTheme.colorScheme.tertiary
                                )
                                Spacer(Modifier.height(16.dp))
                                // Gráfica circular para distribución por tamaño
                                PieChartCard(
                                    title = "Distribución por Tamaño",
                                    data = summary.by_size ?: emptyMap(),
                                    colors = listOf(
                                        Color(0xFF4CAF50),
                                        Color(0xFF2196F3),
                                        Color(0xFFFFC107),
                                        Color(0xFFE91E63),
                                        Color(0xFF9C27B0)
                                    )
                                )

                                Spacer(Modifier.height(16.dp))

                                // Gráfica de barras mejorada para color
                                ImprovedBarChart(
                                    title = "Distribución por Color",
                                    data = summary.by_color ?: emptyMap(),
                                    barColor = MaterialTheme.colorScheme.tertiary
                                )

                                Spacer(Modifier.height(16.dp))

                                val sampleData = mapOf(
                                    "Lote 1" to 15,
                                    "Lote 2" to 22,
                                    "Lote 3" to 18,
                                    "Lote 4" to 25,
                                    "Lote 5" to 20,
                                    "Lote 6" to 28
                                )
                                // Gráfica lineal para peso
                                // Reemplaza el LineChartCard con esto:
                                if (!summary.by_weight.isNullOrEmpty()) {
                                    WeightBarChart(
                                        title = "Distribución por Peso",
                                        data = summary.by_weight,
                                        barColor = MaterialTheme.colorScheme.secondary
                                    )
                                    Spacer(Modifier.height(16.dp))
                                } else {
                                    EmptyWeightChart("Distribución por Peso")
                                    Spacer(Modifier.height(16.dp))
                                }

                                Spacer(Modifier.height(8.dp))

                                Text(
                                    "Fecha: ${summary.date}",
                                    fontSize = 12.sp,
                                    color = MaterialTheme.colorScheme.onSurface.copy(alpha = 0.6f)
                                )
                            } ?: Box(
                                modifier = Modifier
                                    .fillMaxWidth()
                                    .height(200.dp),
                                contentAlignment = Alignment.Center
                            ) {
                                Text("No hay datos disponibles")
                            }
                        }
                    )
                }
            }

            // 📈 Métricas de productividad
            item {
                AnimatedVisibility(
                    visible = animatedVisibility.value > 0.6f,
                    enter = slideInVertically(initialOffsetY = { it / 2 }) + fadeIn(),
                    modifier = Modifier.animateItemPlacement()
                ) {
                    AnalyticsCard(
                        title = "Métricas de Productividad",
                        icon = Icons.AutoMirrored.Outlined.TrendingUp,
                        content = {
                            productivityMetrics?.let { metrics ->
                                // KPI cards
                                Row(
                                    modifier = Modifier.fillMaxWidth(),
                                    horizontalArrangement = Arrangement.SpaceEvenly
                                ) {
                                    MetricCard(
                                        title = "Total Clasificado",
                                        value = "${metrics.total_beans}",
                                        unit = "frijoles",
                                        color = MaterialTheme.colorScheme.primary
                                    )

                                    MetricCard(
                                        title = "Lotes Semana",
                                        value = "${metrics.batches_last_week?.size ?: 0}",
                                        unit = "lotes",
                                        color = MaterialTheme.colorScheme.secondary
                                    )
                                }

                                Spacer(Modifier.height(16.dp))

                                // Gráfica de líneas para lotes de la semana
                                metrics.batches_last_week?.let { batches ->
                                    LineChartCard(
                                        title = "Lotes de la Última Semana",
                                        data = batches,
                                        lineColor = MaterialTheme.colorScheme.primary
                                    )
                                }
                            } ?: Box(
                                modifier = Modifier
                                    .fillMaxWidth()
                                    .height(150.dp),
                                contentAlignment = Alignment.Center
                            ) {
                                Text("No hay métricas disponibles")
                            }
                        }
                    )
                }
            }
        }
    }
}

// Componente reutilizable para tarjetas de analytics
@Composable
fun AnalyticsCard(
    title: String,
    icon: ImageVector,
    content: @Composable ColumnScope.() -> Unit
) {
    Card(
        modifier = Modifier
            .fillMaxWidth()
            .padding(horizontal = 16.dp),
        shape = RoundedCornerShape(20.dp),
        elevation = CardDefaults.cardElevation(8.dp)
    ) {
        Column(
            modifier = Modifier.padding(20.dp)
        ) {
            Row(verticalAlignment = Alignment.CenterVertically) {
                Icon(
                    imageVector = icon,
                    contentDescription = null,
                    tint = MaterialTheme.colorScheme.primary,
                    modifier = Modifier.size(20.dp)
                )
                Spacer(Modifier.width(8.dp))
                Text(
                    text = title,
                    style = MaterialTheme.typography.titleMedium,
                    fontWeight = FontWeight.Bold,
                    color = MaterialTheme.colorScheme.onSurface
                )
            }

            Spacer(Modifier.height(16.dp))

            content()
        }
    }
}

@Composable
fun WeightBarChart(
    title: String,
    data: Map<String, Int>, // Peso -> Cantidad de semillas
    barColor: Color
) {
    val weights = data.keys.sortedBy { it.toFloatOrNull() ?: 0f }
    val counts = weights.map { data[it] ?: 0 }
    val maxCount = counts.maxOrNull() ?: 1

    Card(
        modifier = Modifier
            .fillMaxWidth()
            .padding(horizontal = 16.dp),
        shape = RoundedCornerShape(12.dp),
        colors = CardDefaults.cardColors(containerColor = MaterialTheme.colorScheme.surface),
        elevation = CardDefaults.cardElevation(4.dp)
    ) {
        Column(
            modifier = Modifier
                .fillMaxWidth()
                .padding(16.dp)
        ) {
            Text(
                text = title,
                style = MaterialTheme.typography.titleMedium,
                fontWeight = FontWeight.SemiBold,
                color = MaterialTheme.colorScheme.onSurface
            )

            Spacer(Modifier.height(16.dp))

            Box(
                modifier = Modifier
                    .fillMaxWidth()
                    .height(250.dp)
            ) {
                Canvas(modifier = Modifier.fillMaxSize()) {
                    val paddingLeft = 60f
                    val paddingRight = 30f
                    val paddingTop = 30f
                    val paddingBottom = 60f

                    val chartWidth = size.width - paddingLeft - paddingRight
                    val chartHeight = size.height - paddingTop - paddingBottom

                    // Dibujar eje Y (CANTIDAD - vertical)
                    drawLine(
                        color = Color.Gray.copy(alpha = 0.5f),
                        start = Offset(paddingLeft, paddingTop),
                        end = Offset(paddingLeft, size.height - paddingBottom),
                        strokeWidth = 2f
                    )

                    // Dibujar eje X (PESOS - horizontal)
                    drawLine(
                        color = Color.Gray.copy(alpha = 0.5f),
                        start = Offset(paddingLeft, size.height - paddingBottom),
                        end = Offset(size.width - paddingRight, size.height - paddingBottom),
                        strokeWidth = 2f
                    )

                    // Dibujar líneas de referencia horizontales
                    for (i in 0..4) {
                        val y = paddingTop + chartHeight * (1 - i / 4f)
                        drawLine(
                            color = Color.Gray.copy(alpha = 0.2f),
                            start = Offset(paddingLeft, y),
                            end = Offset(size.width - paddingRight, y),
                            strokeWidth = 1f
                        )

                        // Etiquetas del eje Y (Cantidad)
                        val countValue = (maxCount * (i / 4f)).toInt()
                        drawContext.canvas.nativeCanvas.apply {
                            drawText(
                                countValue.toString(),
                                paddingLeft - 10f,
                                y + 5f,
                                android.graphics.Paint().apply {
                                    color = android.graphics.Color.GRAY
                                    textSize = 20f
                                    textAlign = android.graphics.Paint.Align.RIGHT
                                }
                            )
                        }
                    }

                    // Dibujar barras
                    if (weights.isNotEmpty()) {
                        val barWidth = chartWidth / weights.size * 0.7f
                        val barSpacing = chartWidth / weights.size * 0.3f

                        weights.forEachIndexed { index, weight ->
                            val count = data[weight] ?: 0
                            val x = paddingLeft + index * (barWidth + barSpacing)
                            val barHeight = (count.toFloat() / maxCount) * chartHeight

                            // Dibujar barra
                            drawRoundRect(
                                color = barColor,
                                topLeft = Offset(x, size.height - paddingBottom - barHeight),
                                size = Size(barWidth, barHeight),
                                cornerRadius = CornerRadius(4f, 4f)
                            )

                            // Etiqueta del peso (eje X)
                            drawContext.canvas.nativeCanvas.apply {
                                drawText(
                                    "$weight g",
                                    x + barWidth / 2,
                                    size.height - paddingBottom + 20f,
                                    android.graphics.Paint().apply {
                                        color = android.graphics.Color.GRAY
                                        textSize = 16f
                                        textAlign = android.graphics.Paint.Align.CENTER
                                    }
                                )
                            }

                            // Etiqueta de la cantidad (arriba de la barra)
                            if (count > 0) {
                                drawContext.canvas.nativeCanvas.apply {
                                    drawText(
                                        count.toString(),
                                        x + barWidth / 2,
                                        size.height - paddingBottom - barHeight - 5f,
                                        android.graphics.Paint().apply {
                                            color = android.graphics.Color.BLACK
                                            textSize = 16f
                                            textAlign = android.graphics.Paint.Align.CENTER
                                            typeface = android.graphics.Typeface.DEFAULT_BOLD
                                        }
                                    )
                                }
                            }
                        }
                    }

                    // Etiqueta del eje Y
                    drawContext.canvas.nativeCanvas.apply {
                        drawText(
                            "Cantidad",
                            paddingLeft - 45f,
                            paddingTop - 10f,
                            android.graphics.Paint().apply {
                                color = android.graphics.Color.GRAY
                                textSize = 20f
                                textAlign = android.graphics.Paint.Align.RIGHT
                            }
                        )
                    }

                    // Etiqueta del eje X
                    drawContext.canvas.nativeCanvas.apply {
                        drawText(
                            "Peso (gramos)",
                            size.width / 2,
                            size.height - 20f,
                            android.graphics.Paint().apply {
                                color = android.graphics.Color.GRAY
                                textSize = 20f
                                textAlign = android.graphics.Paint.Align.CENTER
                            }
                        )
                    }
                }
            }

            // Leyenda estadística
            if (data.isNotEmpty()) {
                Spacer(Modifier.height(16.dp))
                val totalSemillas = data.values.sum()

                // Calcular el promedio de manera más explícita
                var sumaPesos = 0f
                data.entries.forEach { (peso, count) ->
                    sumaPesos += (peso.toFloatOrNull() ?: 0f) * count
                }
                val promedio = sumaPesos / totalSemillas

                Row(
                    modifier = Modifier.fillMaxWidth(),
                    horizontalArrangement = Arrangement.SpaceBetween
                ) {
                    Text(
                        text = "Total: $totalSemillas semillas",
                        style = MaterialTheme.typography.bodySmall,
                        color = MaterialTheme.colorScheme.onSurface.copy(alpha = 0.7f)
                    )
                    Text(
                        text = "Promedio: ${"%.2f".format(promedio)} g",
                        style = MaterialTheme.typography.bodySmall,
                        color = MaterialTheme.colorScheme.onSurface.copy(alpha = 0.7f)
                    )
                }
            }
        }
    }
}

@Composable
fun EmptyWeightChart(title: String) {
    Card(
        modifier = Modifier
            .fillMaxWidth()
            .padding(horizontal = 16.dp),
        shape = RoundedCornerShape(12.dp),
        colors = CardDefaults.cardColors(containerColor = MaterialTheme.colorScheme.surface.copy(alpha = 0.5f))
    ) {
        Column(
            modifier = Modifier
                .fillMaxWidth()
                .padding(24.dp),
            horizontalAlignment = Alignment.CenterHorizontally
        ) {
            Icon(
                imageVector = Icons.Outlined.Scale,
                contentDescription = "Sin datos de peso",
                modifier = Modifier.size(32.dp),
                tint = MaterialTheme.colorScheme.onSurface.copy(alpha = 0.5f)
            )
            Spacer(Modifier.height(8.dp))
            Text(
                text = title,
                style = MaterialTheme.typography.titleMedium,
                fontWeight = FontWeight.SemiBold
            )
            Spacer(Modifier.height(4.dp))
            Text(
                text = "No hay datos de peso disponibles",
                style = MaterialTheme.typography.bodyMedium,
                color = MaterialTheme.colorScheme.onSurface.copy(alpha = 0.6f)
            )
        }
    }
}
// Gráfica de barras mejorada
@Composable
fun ImprovedBarChart(
    title: String,
    data: Map<String, Int>,
    barColor: Color
) {
    val maxValue = (data.values.maxOrNull() ?: 1).toFloat()
    val sortedData = data.toList().sortedByDescending { it.second }

    Column {
        Text(
            text = title,
            style = MaterialTheme.typography.bodyLarge,
            fontWeight = FontWeight.Medium,
            color = MaterialTheme.colorScheme.onSurface.copy(alpha = 0.8f)
        )

        Spacer(Modifier.height(12.dp))

        // Barras con etiquetas de valor
        sortedData.forEach { (label, value) ->
            Row(
                modifier = Modifier
                    .fillMaxWidth()
                    .height(28.dp)
                    .padding(vertical = 2.dp),
                verticalAlignment = Alignment.CenterVertically
            ) {
                Text(
                    text = label,
                    modifier = Modifier.width(60.dp),
                    fontSize = 12.sp,
                    fontWeight = FontWeight.Medium
                )

                Spacer(Modifier.width(8.dp))

                // Barra con animación
                val animatedProgress = remember { Animatable(0f) }
                LaunchedEffect(Unit) {
                    animatedProgress.animateTo(value.toFloat() / maxValue)
                }

                Box(
                    modifier = Modifier
                        .weight(1f)
                        .height(20.dp)
                        .clip(RoundedCornerShape(4.dp))
                        .background(MaterialTheme.colorScheme.surfaceVariant)
                ) {
                    Box(
                        modifier = Modifier
                            .fillMaxWidth(animatedProgress.value)
                            .fillMaxHeight()
                            .clip(RoundedCornerShape(4.dp))
                            .background(barColor)
                    )
                }

                Spacer(Modifier.width(8.dp))

                Text(
                    text = value.toString(),
                    fontSize = 12.sp,
                    fontWeight = FontWeight.Bold
                )
            }
        }
    }
}

// Gráfica circular
@Composable
fun PieChartCard(
    title: String,
    data: Map<String, Int>,
    colors: List<Color>
) {
    val total = data.values.sum().toFloat()
    var startAngle = 0f

    Column {
        Text(
            text = title,
            style = MaterialTheme.typography.bodyLarge,
            fontWeight = FontWeight.Medium,
            color = MaterialTheme.colorScheme.onSurface.copy(alpha = 0.8f)
        )

        Spacer(Modifier.height(12.dp))

        Row(
            modifier = Modifier.fillMaxWidth(),
            verticalAlignment = Alignment.CenterVertically
        ) {
            // Gráfica circular
            Box(
                modifier = Modifier.size(120.dp),
                contentAlignment = Alignment.Center
            ) {
                Canvas(modifier = Modifier.size(120.dp)) {
                    data.values.forEachIndexed { index, value ->
                        val sweepAngle = (value / total) * 360f
                        drawArc(
                            color = colors[index % colors.size],
                            startAngle = startAngle,
                            sweepAngle = sweepAngle,
                            useCenter = true
                        )
                        startAngle += sweepAngle
                    }
                }
            }

            Spacer(Modifier.width(16.dp))

            // Leyenda
            Column(
                modifier = Modifier.weight(1f)
            ) {
                data.keys.forEachIndexed { index, label ->
                    Row(
                        modifier = Modifier.padding(vertical = 4.dp),
                        verticalAlignment = Alignment.CenterVertically
                    ) {
                        Box(
                            modifier = Modifier
                                .size(12.dp)
                                .background(colors[index % colors.size], CircleShape)
                        )
                        Spacer(Modifier.width(8.dp))
                        Text(
                            text = label,
                            fontSize = 12.sp,
                            modifier = Modifier.weight(1f)
                        )
                        Text(
                            text = "${((data.values.elementAt(index) / total) * 100).toInt()}%",
                            fontSize = 12.sp,
                            fontWeight = FontWeight.Bold
                        )
                    }
                }
            }
        }
    }
}

// Gráfica de líneas
@OptIn(ExperimentalMaterial3Api::class)
@Composable
fun LineChartCard(
    title: String,
    data: Map<String, Int>, // clave: categoría (semilla), valor: peso
    lineColor: Color
) {
    val categories = data.keys.toList()
    val weights = data.values.toList()

    // Manejar caso cuando no hay datos
    if (weights.isEmpty() || categories.isEmpty()) {
        EmptyChartPlaceholder(title = title)
        return
    }

    val maxWeight = (weights.maxOrNull() ?: 1).toFloat()
    val minWeight = (weights.minOrNull() ?: 0).toFloat()

    // Prevenir división por cero
    val weightRange = if (maxWeight - minWeight == 0f) 1f else maxWeight - minWeight

    Card(
        modifier = Modifier
            .fillMaxWidth()
            .padding(8.dp),
        colors = CardDefaults.cardColors(containerColor = MaterialTheme.colorScheme.surface),
        elevation = CardDefaults.cardElevation(4.dp)
    ) {
        Column(
            modifier = Modifier
                .fillMaxWidth()
                .padding(16.dp)
        ) {
            Text(
                text = title,
                style = MaterialTheme.typography.titleMedium,
                fontWeight = FontWeight.SemiBold,
                color = MaterialTheme.colorScheme.onSurface
            )

            Spacer(Modifier.height(16.dp))

            Box(
                modifier = Modifier
                    .fillMaxWidth()
                    .height(250.dp)
            ) {
                Canvas(modifier = Modifier.fillMaxSize()) {
                    val paddingLeft = 50f
                    val paddingRight = 30f
                    val paddingTop = 30f
                    val paddingBottom = 50f

                    val chartWidth = size.width - paddingLeft - paddingRight
                    val chartHeight = size.height - paddingTop - paddingBottom

                    // Dibujar eje Y (PESOS - vertical)
                    drawLine(
                        color = Color.Gray.copy(alpha = 0.5f),
                        start = Offset(paddingLeft, paddingTop),
                        end = Offset(paddingLeft, size.height - paddingBottom),
                        strokeWidth = 2f
                    )

                    // Dibujar eje X (SEMILLAS/CATEGORÍAS - horizontal)
                    drawLine(
                        color = Color.Gray.copy(alpha = 0.5f),
                        start = Offset(paddingLeft, size.height - paddingBottom),
                        end = Offset(size.width - paddingRight, size.height - paddingBottom),
                        strokeWidth = 2f
                    )

                    // Dibujar líneas de referencia horizontales (para pesos)
                    for (i in 0..4) {
                        val y = paddingTop + chartHeight * (1 - i / 4f)
                        drawLine(
                            color = Color.Gray.copy(alpha = 0.2f),
                            start = Offset(paddingLeft, y),
                            end = Offset(size.width - paddingRight, y),
                            strokeWidth = 1f
                        )

                        // Etiquetas del eje Y (PESOS)
                        val weightValue = (minWeight + weightRange * (i / 4f)).toInt()
                        drawContext.canvas.nativeCanvas.apply {
                            drawText(
                                "$weightValue g",
                                paddingLeft - 10f,
                                y + 5f,
                                android.graphics.Paint().apply {
                                    color = android.graphics.Color.GRAY
                                    textSize = 24f // 12.sp equivalent
                                    textAlign = android.graphics.Paint.Align.RIGHT
                                }
                            )
                        }
                    }

                    // Calcular puntos para la línea
                    val points = weights.mapIndexed { index, weight ->
                        val x = paddingLeft + (index.toFloat() / (weights.size - 1)) * chartWidth
                        val y = paddingTop + chartHeight - ((weight - minWeight) / weightRange) * chartHeight
                        Offset(x, y)
                    }

                    // Dibujar línea de datos
                    if (points.size > 1) {
                        for (i in 0 until points.size - 1) {
                            drawLine(
                                color = lineColor,
                                start = points[i],
                                end = points[i + 1],
                                strokeWidth = 4f,
                                cap = StrokeCap.Round
                            )
                        }
                    }

                    // Dibujar puntos
                    points.forEach { point ->
                        drawCircle(
                            color = lineColor,
                            radius = 6f,
                            center = point
                        )
                        drawCircle(
                            color = Color.White,
                            radius = 2f,
                            center = point
                        )
                    }

                    // Etiquetas del eje X (CATEGORÍAS/SEMILLAS)
                    categories.forEachIndexed { index, category ->
                        val x = paddingLeft + (index.toFloat() / (categories.size - 1)) * chartWidth

                        // Rotar texto para mejor visualización
                        drawContext.canvas.nativeCanvas.apply {
                            save()
                            translate(x, size.height - paddingBottom + 20f)
                            rotate(-45f)
                            drawText(
                                category.take(10), // Limitar longitud
                                0f,
                                0f,
                                android.graphics.Paint().apply {
                                    color = android.graphics.Color.GRAY
                                    textSize = 20f // 10.sp equivalent
                                    textAlign = android.graphics.Paint.Align.CENTER
                                }
                            )
                            restore()
                        }
                    }

                    // Etiqueta del eje Y
                    drawContext.canvas.nativeCanvas.apply {
                        drawText(
                            "Peso (g)",
                            paddingLeft - 40f,
                            paddingTop - 10f,
                            android.graphics.Paint().apply {
                                color = android.graphics.Color.GRAY
                                textSize = 24f
                                textAlign = android.graphics.Paint.Align.RIGHT
                            }
                        )
                    }

                    // Etiqueta del eje X
                    drawContext.canvas.nativeCanvas.apply {
                        drawText(
                            "Semillas",
                            size.width / 2,
                            size.height - 10f,
                            android.graphics.Paint().apply {
                                color = android.graphics.Color.GRAY
                                textSize = 24f
                                textAlign = android.graphics.Paint.Align.CENTER
                            }
                        )
                    }
                }
            }
        }
    }
}

@Composable
fun EmptyChartPlaceholder(title: String) {
    Card(
        modifier = Modifier
            .fillMaxWidth()
            .padding(8.dp),
        colors = CardDefaults.cardColors(containerColor = MaterialTheme.colorScheme.surface.copy(alpha = 0.5f))
    ) {
        Column(
            modifier = Modifier
                .fillMaxWidth()
                .padding(24.dp),
            horizontalAlignment = Alignment.CenterHorizontally
        ) {
            Icon(
                imageVector = Icons.Outlined.BarChart,
                contentDescription = "Sin datos",
                modifier = Modifier.size(32.dp),
                tint = MaterialTheme.colorScheme.onSurface.copy(alpha = 0.5f)
            )
            Spacer(Modifier.height(8.dp))
            Text(
                text = title,
                style = MaterialTheme.typography.titleMedium,
                fontWeight = FontWeight.SemiBold
            )
            Spacer(Modifier.height(4.dp))
            Text(
                text = "No hay datos de peso disponibles",
                style = MaterialTheme.typography.bodyMedium,
                color = MaterialTheme.colorScheme.onSurface.copy(alpha = 0.6f)
            )
        }
    }
}

// Tarjeta de métrica
@Composable
fun MetricCard(
    title: String,
    value: String,
    unit: String,
    color: Color
) {
    Card(
        shape = RoundedCornerShape(12.dp),
        colors = CardDefaults.cardColors(
            containerColor = color.copy(alpha = 0.1f)
        ),
        elevation = CardDefaults.cardElevation(2.dp)
    ) {
        Column(
            modifier = Modifier.padding(12.dp),
            horizontalAlignment = Alignment.CenterHorizontally
        ) {
            Text(
                text = value,
                style = MaterialTheme.typography.titleLarge,
                fontWeight = FontWeight.Bold,
                color = color
            )
            Text(
                text = title,
                style = MaterialTheme.typography.bodySmall,
                color = color
            )
            Text(
                text = unit,
                style = MaterialTheme.typography.bodySmall,
                color = color.copy(alpha = 0.7f)
            )
        }
    }
}