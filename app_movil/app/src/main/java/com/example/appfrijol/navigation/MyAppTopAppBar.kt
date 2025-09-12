package com.example.appfrijol.navigation

import androidx.compose.foundation.layout.height
import androidx.compose.material3.CenterAlignedTopAppBar
import androidx.compose.material3.ExperimentalMaterial3Api
import androidx.compose.material3.MaterialTheme
import androidx.compose.material3.Text
import androidx.compose.material3.TopAppBarDefaults
import androidx.compose.runtime.Composable
import androidx.compose.ui.Modifier
import androidx.compose.ui.text.font.FontWeight
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp

@OptIn(ExperimentalMaterial3Api::class)
@Composable
fun MyAppTopAppBar() { // Nombre descriptivo para tu TopAppBar personalizada
    CenterAlignedTopAppBar(
        modifier = Modifier.height(70.dp), // Altura deseada
        title = {
            Text(
                text = "FrijolTech",
                style = MaterialTheme.typography.titleMedium.copy( // Estilo de texto deseado
                    fontWeight = FontWeight.Bold,
                    fontSize = 28.sp
                )
            )
        },
        colors = TopAppBarDefaults.centerAlignedTopAppBarColors(
            containerColor = MaterialTheme.colorScheme.primary,
            titleContentColor = MaterialTheme.colorScheme.secondary
        )
    )
}