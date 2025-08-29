package com.example.appfrijol.navigation

sealed class Screen(val route: String) {
    object Welcome : Screen("welcome")          // Pantalla inicial (LoginFlowHandler)
    object Home : Screen("home")         // Pantalla de HomeScreen
    object Register : Screen("register")       // Registro
}
