package com.example.appfrijol.navigation

import androidx.compose.material.icons.Icons
import androidx.compose.material.icons.filled.BarChart
import androidx.compose.material.icons.filled.Home
import androidx.compose.material.icons.filled.MoreHoriz
import androidx.compose.material.icons.filled.Person
import androidx.compose.material.icons.filled.School
import androidx.compose.ui.graphics.vector.ImageVector


sealed class BottomNavItem(
    val route: String,
    val icon: ImageVector,
    val label: String // 👈 siempre en español (UI)
) {
    object Results : BottomNavItem("results", Icons.Default.BarChart, "Resultados")
    object Learning : BottomNavItem("learning", Icons.Default.School, "Capacitación")
    object Home : BottomNavItem("home", Icons.Default.Home, "Inicio") // centro
    object Profile : BottomNavItem("profile", Icons.Default.Person, "Perfil")
    object More : BottomNavItem("more", Icons.Default.MoreHoriz, "Más")
}