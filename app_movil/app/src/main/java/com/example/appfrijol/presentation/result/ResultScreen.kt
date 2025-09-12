package com.example.appfrijol.presentation.result

import android.util.Log
import android.widget.Toast
import androidx.compose.foundation.BorderStroke
import androidx.compose.foundation.Canvas
import androidx.compose.foundation.ExperimentalFoundationApi
import androidx.compose.foundation.background
import androidx.compose.foundation.clickable
import androidx.compose.foundation.layout.Arrangement
import androidx.compose.foundation.layout.Box
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.ColumnScope
import androidx.compose.foundation.layout.Row
import androidx.compose.foundation.layout.Spacer
import androidx.compose.foundation.layout.fillMaxSize
import androidx.compose.foundation.layout.fillMaxWidth
import androidx.compose.foundation.layout.height
import androidx.compose.foundation.layout.padding
import androidx.compose.foundation.layout.size
import androidx.compose.foundation.layout.width
import androidx.compose.foundation.lazy.LazyColumn
import androidx.compose.foundation.lazy.itemsIndexed
import androidx.compose.foundation.rememberScrollState
import androidx.compose.foundation.shape.CircleShape
import androidx.compose.foundation.shape.RoundedCornerShape
import androidx.compose.foundation.verticalScroll
import androidx.compose.material.icons.Icons
import androidx.compose.material.icons.filled.Compare
import androidx.compose.material.icons.filled.PictureAsPdf
import androidx.compose.material.icons.filled.TableChart
import androidx.compose.material.icons.filled.Warning
import androidx.compose.material.icons.outlined.Inventory2
import androidx.compose.material3.BottomSheetDefaults
import androidx.compose.material3.Button
import androidx.compose.material3.ButtonDefaults
import androidx.compose.material3.Card
import androidx.compose.material3.CardColors
import androidx.compose.material3.CardDefaults
import androidx.compose.material3.CardElevation
import androidx.compose.material3.Checkbox
import androidx.compose.material3.CheckboxDefaults
import androidx.compose.material3.ExperimentalMaterial3Api
import androidx.compose.material3.Icon
import androidx.compose.material3.MaterialTheme
import androidx.compose.material3.ModalBottomSheet
import androidx.compose.material3.OutlinedButton
import androidx.compose.material3.Scaffold
import androidx.compose.material3.Tab
import androidx.compose.material3.TabRow
import androidx.compose.material3.Text
import androidx.compose.material3.rememberModalBottomSheetState
import androidx.compose.runtime.Composable
import androidx.compose.runtime.LaunchedEffect
import androidx.compose.runtime.collectAsState
import androidx.compose.runtime.getValue
import androidx.compose.runtime.mutableStateOf
import androidx.compose.runtime.remember
import androidx.compose.runtime.rememberCoroutineScope
import androidx.compose.runtime.setValue
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.geometry.CornerRadius
import androidx.compose.ui.geometry.Offset
import androidx.compose.ui.geometry.Size
import androidx.compose.ui.graphics.Brush
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.graphics.Shape
import androidx.compose.ui.graphics.nativeCanvas
import androidx.compose.ui.platform.LocalContext
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp
import androidx.hilt.navigation.compose.hiltViewModel
import com.example.appfrijol.domain.model.Semilla
import com.example.appfrijol.presentation.session.SessionViewModel
import kotlinx.coroutines.launch

@OptIn(ExperimentalMaterial3Api::class, ExperimentalFoundationApi::class)
@Composable
fun ResultScreen(
    sessionViewModel: SessionViewModel = hiltViewModel(),
    semillaViewModel: SemillaViewModel = hiltViewModel()
) {
    val context = LocalContext.current
    val userName by sessionViewModel.userName.collectAsState()
    val lotes by semillaViewModel.historial.collectAsState()
    val error by semillaViewModel.error.collectAsState()

    val sheetState = rememberModalBottomSheetState(skipPartiallyExpanded = false)
    val coroutineScope = rememberCoroutineScope()

    var semillasEnLote by remember { mutableStateOf<List<Semilla>>(emptyList()) }
    var tituloLote by remember { mutableStateOf("") }
    var selectedTab by remember { mutableStateOf(0) }

    LaunchedEffect(Unit) { semillaViewModel.cargarHistorial() }

    // Colores personalizados
    val primaryColor = MaterialTheme.colorScheme.primary
    val surfaceColor = MaterialTheme.colorScheme.surface
    val onSurfaceColor = MaterialTheme.colorScheme.onSurface
    val gradientColors = listOf(
        MaterialTheme.colorScheme.primary,
        MaterialTheme.colorScheme.secondary,
        MaterialTheme.colorScheme.tertiary
    )

    Scaffold(
    ) { innerPadding ->
        Box(
            modifier = Modifier
                .fillMaxSize()
                .padding(innerPadding)
                .background(
                    brush = Brush.verticalGradient(
                        colors = listOf(
                            MaterialTheme.colorScheme.primaryContainer.copy(alpha = 0.2f),
                            MaterialTheme.colorScheme.background
                        )
                    )
                )
        ) {
            Column(
                modifier = Modifier
                    .fillMaxSize()
                    .verticalScroll(rememberScrollState())
                    .padding(16.dp)
            ) {
                // Encabezado de bienvenida
                Card(
                    modifier = Modifier.fillMaxWidth(),
                    colors = CardDefaults.cardColors(containerColor = MaterialTheme.colorScheme.surface),
                    elevation = CardDefaults.cardElevation(4.dp),
                    shape = RoundedCornerShape(12.dp)
                ) {
                    Column(modifier = Modifier.padding(16.dp)) {
                        Text(
                            text = "Hola, $userName!",
                            style = MaterialTheme.typography.headlineSmall,
                            fontWeight = FontWeight.Bold,
                            color = primaryColor
                        )
                        Spacer(modifier = Modifier.height(4.dp))
                        Text(
                            text = "Revisa el historial de lotes procesados",
                            style = MaterialTheme.typography.bodyMedium,
                            color = onSurfaceColor.copy(alpha = 0.7f)
                        )
                    }
                }

                Spacer(modifier = Modifier.height(16.dp))

                error?.let {
                    Card(
                        modifier = Modifier.fillMaxWidth(),
                        colors = CardDefaults.cardColors(containerColor = MaterialTheme.colorScheme.errorContainer),
                        shape = RoundedCornerShape(12.dp)
                    ) {
                        Row(
                            modifier = Modifier.padding(12.dp),
                            verticalAlignment = Alignment.CenterVertically
                        ) {
                            Icon(
                                imageVector = Icons.Default.Warning,
                                contentDescription = "Error",
                                tint = MaterialTheme.colorScheme.error
                            )
                            Spacer(modifier = Modifier.width(8.dp))
                            Text(
                                text = it,
                                color = MaterialTheme.colorScheme.error,
                                style = MaterialTheme.typography.bodyMedium
                            )
                        }
                    }
                    Spacer(modifier = Modifier.height(8.dp))
                }

                // Lista de lotes
                Text(
                    text = "Tus Lotes Registrados",
                    style = MaterialTheme.typography.titleMedium,
                    fontWeight = FontWeight.SemiBold,
                    modifier = Modifier.padding(vertical = 8.dp)
                )

                if (lotes.isEmpty()) {
                    Column(
                        modifier = Modifier
                            .fillMaxWidth()
                            .padding(32.dp),
                        horizontalAlignment = Alignment.CenterHorizontally
                    ) {
                        Icon(
                            imageVector = Icons.Outlined.Inventory2,
                            contentDescription = "Sin lotes",
                            tint = MaterialTheme.colorScheme.onSurface.copy(alpha = 0.5f),
                            modifier = Modifier.size(64.dp)
                        )
                        Spacer(modifier = Modifier.height(16.dp))
                        Text(
                            text = "No hay lotes registrados",
                            style = MaterialTheme.typography.bodyLarge,
                            color = MaterialTheme.colorScheme.onSurface.copy(alpha = 0.7f)
                        )
                    }
                } else {
                    Column(
                        modifier = Modifier.fillMaxWidth(),
                        verticalArrangement = Arrangement.spacedBy(12.dp)
                    ) {
                        lotes.forEachIndexed { index, lote ->
                            AnimatedCard(
                                modifier = Modifier
                                    .fillMaxWidth()
                                    .clickable {
                                        semillasEnLote = lote.semillas
                                        tituloLote =
                                            "Lote ${index + 1}: ${lote.inicio} - ${lote.fin}"
                                        coroutineScope.launch { sheetState.show() }
                                    }
                                    ,
                                elevation = CardDefaults.cardElevation(2.dp),
                                colors = CardDefaults.cardColors(containerColor = surfaceColor)
                            ) {
                                Row(
                                    modifier = Modifier
                                        .fillMaxWidth()
                                        .padding(16.dp),
                                    horizontalArrangement = Arrangement.SpaceBetween,
                                    verticalAlignment = Alignment.CenterVertically
                                ) {
                                    Column(modifier = Modifier.weight(1f)) {
                                        Text(
                                            text = "Lote ${index + 1}",
                                            style = MaterialTheme.typography.titleSmall,
                                            fontWeight = FontWeight.Bold
                                        )
                                        Spacer(modifier = Modifier.height(4.dp))
                                        Text(
                                            text = "${lote.inicio} - ${lote.fin}",
                                            style = MaterialTheme.typography.bodySmall,
                                            color = onSurfaceColor.copy(alpha = 0.7f)
                                        )
                                        Spacer(modifier = Modifier.height(4.dp))
                                        Text(
                                            text = "${lote.total} semillas",
                                            style = MaterialTheme.typography.bodyMedium,
                                            color = primaryColor
                                        )
                                    }

                                    Checkbox(
                                        checked = semillaViewModel.selectedIds.contains(index),
                                        onCheckedChange = { checked ->
                                            if (checked) semillaViewModel.selectedIds.add(index)
                                            else semillaViewModel.selectedIds.remove(index)
                                        },
                                        colors = CheckboxDefaults.colors(checkedColor = primaryColor)
                                    )
                                }
                            }
                        }
                    }
                }

                Spacer(modifier = Modifier.height(16.dp))

                // Botón de comparación
                Button(
                    onClick = { /* implementar comparación */ },
                    enabled = semillaViewModel.selectedIds.size >= 2,
                    modifier = Modifier.fillMaxWidth(),
                    colors = ButtonDefaults.buttonColors(
                        containerColor = primaryColor,
                        disabledContainerColor = primaryColor.copy(alpha = 0.3f)
                    ),
                    shape = RoundedCornerShape(12.dp),
                    elevation = ButtonDefaults.buttonElevation(
                        defaultElevation = 4.dp,
                        pressedElevation = 8.dp
                    )
                ) {
                    Icon(
                        imageVector = Icons.Default.Compare,
                        contentDescription = "Comparar",
                        modifier = Modifier.size(20.dp)
                    )
                    Spacer(Modifier.width(8.dp))
                    Text(
                        text = "Comparar ${semillaViewModel.selectedIds.size} lotes",
                        style = MaterialTheme.typography.bodyLarge,
                        fontWeight = FontWeight.Medium
                    )
                }

                Spacer(modifier = Modifier.height(24.dp))
            }

            // --- Modal Bottom Sheet Mejorado ---
            if (sheetState.isVisible) {
                ModalBottomSheet(
                    onDismissRequest = { coroutineScope.launch { sheetState.hide() } },
                    sheetState = sheetState,
                    containerColor = MaterialTheme.colorScheme.surface,
                    tonalElevation = 16.dp,
                    dragHandle = { BottomSheetDefaults.DragHandle() },
                    shape = RoundedCornerShape(topStart = 16.dp, topEnd = 16.dp)
                ) {
                    Column(
                        modifier = Modifier
                            .fillMaxWidth()
                            .padding(horizontal = 16.dp, vertical = 8.dp)
                    ) {
                        Text(
                            text = tituloLote,
                            style = MaterialTheme.typography.titleLarge,
                            fontWeight = FontWeight.Bold,
                            modifier = Modifier.padding(bottom = 8.dp)
                        )

                        // Tabs para diferentes visualizaciones
                        TabRow(
                            selectedTabIndex = selectedTab,
                            containerColor = MaterialTheme.colorScheme.surface,
                            contentColor = primaryColor,
                            modifier = Modifier.fillMaxWidth()
                        ) {
                            Tab(
                                selected = selectedTab == 0,
                                onClick = { selectedTab = 0 },
                                text = { Text("Gráficos") }
                            )
                            Tab(
                                selected = selectedTab == 1,
                                onClick = { selectedTab = 1 },
                                text = { Text("Datos") }
                            )
                        }

                        Spacer(modifier = Modifier.height(16.dp))

                        when (selectedTab) {
                            0 -> GraficasTab(semillasEnLote)
                            1 -> DatosTab(semillasEnLote)
                        }

                        Spacer(modifier = Modifier.height(16.dp))

                        // Botones de exportación
                        Row(
                            modifier = Modifier.fillMaxWidth(),
                            horizontalArrangement = Arrangement.SpaceEvenly
                        ) {
                            val fechaInicio = semillasEnLote.firstOrNull()?.fechaRegistro?.replace(":", "-") ?: "inicio"
                            val fechaFin = semillasEnLote.lastOrNull()?.fechaRegistro?.replace(":", "-") ?: "fin"

                            OutlinedButton(
                                onClick = {
                                    coroutineScope.launch {
                                        val result = semillaViewModel.exportarLote(semillasEnLote, "csv")
                                        result.onSuccess { data ->
                                            semillaViewModel.guardarYAbrirArchivo(
                                                context,
                                                data,
                                                "lote_${fechaInicio}_a_${fechaFin}.csv",
                                                "csv"
                                            )
                                        }.onFailure { e ->
                                            Toast.makeText(context, "Error: ${e.message}", Toast.LENGTH_SHORT).show()
                                        }
                                    }
                                },
                                shape = RoundedCornerShape(12.dp),
                                border = BorderStroke(1.dp, primaryColor),
                                colors = ButtonDefaults.outlinedButtonColors(contentColor = primaryColor)
                            ) {
                                Icon(Icons.Default.TableChart, contentDescription = "CSV")
                                Spacer(Modifier.width(8.dp))
                                Text("CSV")
                            }

                            Button(
                                onClick = {
                                    coroutineScope.launch {
                                        val result = semillaViewModel.exportarLote(semillasEnLote, "pdf")
                                        result.onSuccess { data ->
                                            semillaViewModel.guardarYAbrirArchivo(
                                                context,
                                                data,
                                                "lote_${fechaInicio}_a_${fechaFin}.pdf",
                                                "pdf"
                                            )
                                        }.onFailure { e ->
                                            Toast.makeText(context, "Error: ${e.message}", Toast.LENGTH_SHORT).show()
                                        }
                                    }
                                },
                                shape = RoundedCornerShape(12.dp),
                                colors = ButtonDefaults.buttonColors(containerColor = primaryColor)
                            ) {
                                Icon(Icons.Default.PictureAsPdf, contentDescription = "PDF")
                                Spacer(Modifier.width(8.dp))
                                Text("PDF")
                            }
                        }

                        Spacer(modifier = Modifier.height(24.dp))
                    }
                }
            }
        }
    }
}

@Composable
fun GraficasTab(semillasEnLote: List<Semilla>) {
    val context = LocalContext.current

    if (semillasEnLote.isEmpty()) {
        Box(
            modifier = Modifier
                .fillMaxWidth()
                .height(200.dp),
            contentAlignment = Alignment.Center
        ) {
            Text("No hay datos para mostrar", color = MaterialTheme.colorScheme.onSurface.copy(alpha = 0.5f))
        }
        return
    }

    // Datos para gráficos
    val datosPorColor = semillasEnLote.groupBy { it.color.lowercase() }
        .mapValues { it.value.size }

    val datosPorPeso: Map<String, Int> = semillasEnLote.groupBy { semilla ->
        when {
            semilla.peso >= 0.0 && semilla.peso <= 20.0 -> "0-20"  // Rango inclusivo
            semilla.peso > 20.0 && semilla.peso <= 40.0 -> "21-40" // Ajusta los límites como necesites
            semilla.peso > 40.0 && semilla.peso <= 60.0 -> "41-60"
            semilla.peso > 60.0 && semilla.peso <= 80.0 -> "61-80"
            semilla.peso > 80.0 && semilla.peso <= 100.0 -> "81-100" // Asumiendo un límite superior
            else -> {
                // Decidir qué hacer con pesos fuera de los rangos esperados
                // Podría ser "Otros", o si esperas que todos caigan en 0-100:
                if (semilla.peso > 100.0) "81-100" // O ">100"
                else if (semilla.peso < 0.0) "<0" // O agrupar negativos en "0-20"
                else "Desconocido" // O lanzar una excepción si es un estado inválido
            }
        }
    }.mapValues { entry ->
        entry.value.size
    }

    Column(
        modifier = Modifier
            .fillMaxWidth()
            .verticalScroll(rememberScrollState())
    ) {
        // Gráfico de colores
        Text(
            "Distribución por Color",
            style = MaterialTheme.typography.titleMedium,
            modifier = Modifier.padding(vertical = 8.dp)
        )
        GraficoCircular(
            datos = datosPorColor,
            modifier = Modifier
                .fillMaxWidth()
                .height(200.dp)
                .padding(8.dp)
        )

        Spacer(modifier = Modifier.height(16.dp))

        // Gráfico de pesos
        Text(
            "Distribución por Peso",
            style = MaterialTheme.typography.titleMedium,
            modifier = Modifier.padding(vertical = 8.dp)
        )
        GraficoBarras(
            datos = datosPorPeso,
            modifier = Modifier
                .fillMaxWidth()
                .height(200.dp)
                .padding(8.dp)
        )
    }
}

@Composable
fun DatosTab(semillasEnLote: List<Semilla>) {
    if (semillasEnLote.isEmpty()) {
        Box(
            modifier = Modifier
                .fillMaxWidth()
                .height(200.dp),
            contentAlignment = Alignment.Center
        ) {
            Text("No hay datos para mostrar", color = MaterialTheme.colorScheme.onSurface.copy(alpha = 0.5f))
        }
        return
    }

    LazyColumn(
        modifier = Modifier
            .fillMaxWidth()
            .height(300.dp)
    ) {
        itemsIndexed(semillasEnLote) { index, semilla ->
            Card(
                modifier = Modifier
                    .fillMaxWidth()
                    .padding(vertical = 4.dp),
                elevation = CardDefaults.cardElevation(2.dp),
                colors = CardDefaults.cardColors(
                    containerColor = MaterialTheme.colorScheme.surfaceVariant
                )
            ) {
                Row(
                    modifier = Modifier.padding(12.dp),
                    verticalAlignment = Alignment.CenterVertically
                ) {
                    // Indicador de color
                    Box(
                        modifier = Modifier
                            .size(16.dp)
                            .background(
                                color = when (semilla.color.lowercase()) {
                                    "malo" -> Color.Red
                                    "bueno" -> Color.Green

                                    else -> Color.Gray
                                },
                                shape = CircleShape
                            )
                    )

                    Spacer(modifier = Modifier.width(12.dp))

                    Column(modifier = Modifier.weight(1f)) {
                        Text(
                            text = "Semilla ${index + 1}",
                            style = MaterialTheme.typography.bodyMedium,
                            fontWeight = FontWeight.Medium
                        )
                        Text(
                            text = "Color: ${semilla.color}, Peso: ${semilla.peso}",
                            style = MaterialTheme.typography.bodySmall
                        )
                    }

                    Text(
                        text = semilla.fechaRegistro,
                        style = MaterialTheme.typography.bodySmall,
                        color = MaterialTheme.colorScheme.onSurface.copy(alpha = 0.6f)
                    )
                }
            }
        }
    }
}

@Composable
fun GraficoCircular(datos: Map<String, Int>, modifier: Modifier = Modifier) {
    val total = datos.values.sum().toFloat()

    Canvas(modifier = modifier) {
        var startAngle = 0f

        datos.entries.forEach { (key, value) ->
            val sweepAngle = (value / total) * 360f
            Log.d("GraficoCircular", "Clave recibida: '$key'")

            // 🔑 Aquí decides el color según el texto que viene de la API
            val color = when (key.trim().lowercase()) {
                "bueno" -> Color(0xFF4CAF50) // verde
                "malo" -> Color(0xFFF44336)  // rojo
                else -> Color.Gray
            // fallback
            }

            drawArc(
                color = color,
                startAngle = startAngle,
                sweepAngle = sweepAngle,
                useCenter = true,
                size = Size(size.minDimension, size.minDimension),
                topLeft = Offset(
                    (size.width - size.minDimension) / 2,
                    (size.height - size.minDimension) / 2
                )
            )

            startAngle += sweepAngle
        }
    }
}


@Composable
fun GraficoBarras(datos: Map<String, Int>, modifier: Modifier = Modifier) {
    val maxValue = datos.values.maxOrNull()?.toFloat() ?: 1f
    val colors = listOf(
        Color(0xFF4FC3F7),
        Color(0xFF29B6F6),
        Color(0xFF03A9F4),
        Color(0xFF039BE5),
        Color(0xFF0288D1)
    )

    Canvas(modifier = modifier) {
        val barWidth = size.width / (datos.size * 2f)
        val spacing = barWidth / 2

        datos.entries.forEachIndexed { index, (key, value) ->
            val barHeight = (value / maxValue) * size.height * 0.8f
            val x = index * (barWidth + spacing) + spacing
            val y = size.height - barHeight

            val barColor = when (key.trim().lowercase()) {
                "bueno" -> Color(0xFF4CAF50)
                "malo"  -> Color(0xFFF44336)
                else    -> Color.Gray
            }

            drawRoundRect(
                color = barColor,
                topLeft = Offset(x, y),
                size = Size(barWidth, barHeight),
                cornerRadius = CornerRadius(4.dp.toPx())
            )

            // Etiqueta de valor
            drawContext.canvas.nativeCanvas.apply {
                drawText(
                    value.toString(),
                    x + barWidth / 2,
                    y - 8.dp.toPx(),
                    android.graphics.Paint().apply {
                        color = android.graphics.Color.BLACK
                        textSize = 12.sp.toPx()
                        textAlign = android.graphics.Paint.Align.CENTER
                    }
                )
            }

            // Etiqueta de categoría
            drawContext.canvas.nativeCanvas.apply {
                drawText(
                    key,
                    x + barWidth / 2,
                    size.height - 4.dp.toPx(),
                    android.graphics.Paint().apply {
                        color = android.graphics.Color.BLACK
                        textSize = 10.sp.toPx()
                        textAlign = android.graphics.Paint.Align.CENTER
                    }
                )
            }
        }
    }
}

@Composable
fun AnimatedCard(
    modifier: Modifier = Modifier,
    shape: Shape = CardDefaults.shape,
    colors: CardColors = CardDefaults.cardColors(),
    elevation: CardElevation = CardDefaults.cardElevation(defaultElevation = 2.dp), // Elevación por defecto para el Card
    content: @Composable ColumnScope.() -> Unit
) {
    Card(
        modifier = modifier,
        shape = shape,
        colors = colors,
        elevation = elevation, // Simplemente pasa el objeto CardElevation
        content = content
    )
}