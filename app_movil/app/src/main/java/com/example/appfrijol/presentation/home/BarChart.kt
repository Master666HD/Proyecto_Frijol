package com.example.appfrijol.presentation.home

import androidx.compose.foundation.Canvas
import androidx.compose.foundation.layout.Arrangement
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.Row
import androidx.compose.foundation.layout.fillMaxWidth
import androidx.compose.foundation.layout.height
import androidx.compose.foundation.layout.padding
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.ui.Modifier
import androidx.compose.ui.geometry.Offset
import androidx.compose.ui.geometry.Size
import androidx.compose.ui.graphics.Color
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp

@Composable
fun BarChart(title: String, data: Map<String, Int>) {
    val maxValue = (data.values.maxOrNull() ?: 1).toFloat()

    Column {
        Text(title, fontWeight = FontWeight.SemiBold)
        Canvas(modifier = Modifier
            .fillMaxWidth()
            .height(150.dp)
            .padding(top = 8.dp)
        ) {
            val barWidth = size.width / (data.size * 2)
            data.entries.forEachIndexed { index, entry ->
                val barHeight = (entry.value / maxValue) * size.height
                drawRect(
                    color = Color(0xFF4CAF50),
                    topLeft = Offset(
                        x = index * 2 * barWidth,
                        y = size.height - barHeight
                    ),
                    size = Size(barWidth, barHeight)
                )
            }
        }

        // Etiquetas abajo
        Row(
            horizontalArrangement = Arrangement.SpaceEvenly,
            modifier = Modifier.fillMaxWidth()
        ) {
            data.keys.forEach { key ->
                Text(key, fontSize = 12.sp)
            }
        }
    }
}
