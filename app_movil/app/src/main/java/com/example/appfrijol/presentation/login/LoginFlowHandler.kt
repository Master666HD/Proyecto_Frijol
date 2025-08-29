package com.example.appfrijol.presentation.login

import androidx.compose.material3.ExperimentalMaterial3Api
import androidx.compose.material3.rememberModalBottomSheetState
import androidx.compose.runtime.Composable
import androidx.compose.runtime.collectAsState
import androidx.compose.runtime.getValue
import androidx.compose.runtime.mutableStateOf
import androidx.compose.runtime.remember
import androidx.compose.runtime.setValue
import androidx.hilt.navigation.compose.hiltViewModel
import androidx.navigation.NavController
import com.example.appfrijol.navigation.Screen
import com.example.appfrijol.presentation.session.SessionViewModel

@OptIn(ExperimentalMaterial3Api::class)
@Composable
fun LoginFlowHandler(
    navController: NavController,
    openLoginModal: Boolean = false,
    loginViewModel: LoginViewModel = hiltViewModel(),
    sessionViewModel: SessionViewModel = hiltViewModel(),
    content: @Composable (onButtonClick: () -> Unit, userName: String?) -> Unit
) {
    val sheetState = rememberModalBottomSheetState(skipPartiallyExpanded = true)
    var showLogin by remember { mutableStateOf(openLoginModal) }

    val isLoggedIn by sessionViewModel.isLoggedIn.collectAsState()
    val userName by sessionViewModel.userName.collectAsState()

    // 🔹 Eliminamos LaunchedEffect que navegaba automáticamente

    // Ejecuta el contenido pasado desde afuera (por ejemplo WelcomeScreen)
    content({
        if (isLoggedIn && !userName.isNullOrEmpty()) {
            // Si ya está logueado → ir directo a Home
            navController.navigate(Screen.Home.route) {
                popUpTo(Screen.Welcome.route) { inclusive = true }
            }
        } else {
            // Si no → abrir modal
            showLogin = true
        }
    }, userName)

    // Modal de login
    if (showLogin) {
        LoginModal(
            navController = navController,
            sheetState = sheetState,
            loginViewModel = loginViewModel,
            onDismiss = { showLogin = false },
            onLoginSuccess = { user ->
                sessionViewModel.login(
                    token = user.token,
                    userName = user.firstName ?: user.userName ?: "",
                    userId = user.id ?: ""   // 👈 agrega el id aquí
                )
                showLogin = false
                navController.navigate(Screen.Home.route) {
                    popUpTo(Screen.Welcome.route) { inclusive = true }
                }
            }

        )
    }
}

