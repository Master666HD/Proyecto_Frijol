package com.example.appfrijol.navigation

import RegisterScreen
import androidx.compose.runtime.Composable
import androidx.hilt.navigation.compose.hiltViewModel
import androidx.navigation.compose.NavHost
import androidx.navigation.compose.composable
import androidx.navigation.compose.rememberNavController
import com.example.appfrijol.presentation.login.LoginFlowHandler
import com.example.appfrijol.presentation.session.SessionViewModel
import com.example.appfrijol.presentation.welcome.WelcomeScreen


@Composable
fun AppNavigator(
    sessionViewModel: SessionViewModel = hiltViewModel()
) {
    val navController = rememberNavController()

    NavHost(
        navController = navController,
        startDestination = Screen.Welcome.route
    ) {
        // ---------- Welcome ----------
        composable(Screen.Welcome.route) {
            LoginFlowHandler(navController = navController) { onButtonClick, userName ->
                WelcomeScreen(
                    userName = userName,
                    onLoginClick = onButtonClick
                )
            }
        }

        // ---------- Home con BottomNav ----------
        composable(Screen.Home.route) {
            HomeWithBottomNav(
                sessionViewModel = sessionViewModel,
                onLogout = {
                    sessionViewModel.logout()
                    navController.navigate(Screen.Welcome.route) {
                        popUpTo(Screen.Home.route) { inclusive = true }
                    }
                }
            )
        }

        // ---------- Registro ----------
        composable(Screen.Register.route) {
            RegisterScreen(
                onRegisterSuccess = {
                    navController.navigate(Screen.Welcome.route) {
                        popUpTo(Screen.Welcome.route) { inclusive = true }
                    }
                },
                onNavigateToLogin = {
                    navController.navigate(Screen.Welcome.route + "?openLogin=true") {
                        popUpTo(Screen.Welcome.route) { inclusive = true }
                    }
                }
            )
        }
    }
}