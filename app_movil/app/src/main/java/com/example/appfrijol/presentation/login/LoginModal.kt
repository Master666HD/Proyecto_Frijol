package com.example.appfrijol.presentation.login

import android.widget.Toast
import androidx.compose.foundation.layout.Column
import androidx.compose.foundation.layout.fillMaxWidth
import androidx.compose.foundation.layout.heightIn
import androidx.compose.foundation.layout.imePadding
import androidx.compose.foundation.layout.padding
import androidx.compose.foundation.rememberScrollState
import androidx.compose.foundation.verticalScroll
import androidx.compose.material3.ExperimentalMaterial3Api
import androidx.compose.material3.ModalBottomSheet
import androidx.compose.material3.SheetState
import androidx.compose.runtime.Composable
import androidx.compose.ui.Modifier
import androidx.compose.ui.platform.LocalContext
import androidx.compose.ui.platform.LocalSoftwareKeyboardController
import androidx.compose.ui.unit.dp
import androidx.navigation.NavController
import com.example.appfrijol.domain.model.UserInfo
import com.example.appfrijol.navigation.Screen

@OptIn(ExperimentalMaterial3Api::class)
@Composable
fun LoginModal(
    navController: NavController,
    sheetState: SheetState,
    loginViewModel: LoginViewModel,
    onDismiss: () -> Unit,
    onLoginSuccess: (UserInfo) -> Unit   // <- agregar este parámetro
) {
    val context = LocalContext.current
    val keyboardController = LocalSoftwareKeyboardController.current

    ModalBottomSheet(
        modifier = Modifier
            .fillMaxWidth()
            .heightIn(min = 500.dp),
        onDismissRequest = {
            keyboardController?.hide()
            onDismiss()
        },
        sheetState = sheetState
    ) {
        Column(
            modifier = Modifier
                .fillMaxWidth()
                .imePadding()
                .verticalScroll(rememberScrollState())
                .padding(horizontal = 24.dp, vertical = 16.dp)
        ) {
            LoginScreen(
                onLoginSuccess = { user ->
                    onDismiss()
                    onLoginSuccess(user)   // <- llama al callback pasado
                    Toast.makeText(
                        context,
                        "¡Bienvenido ${user.firstName}!",
                        Toast.LENGTH_SHORT
                    ).show()
                    navController.navigate(Screen.Home.route) {
                        popUpTo(Screen.Welcome.route) { inclusive = false }
                    }
                },
                onNavigateToRegister = {
                    onDismiss()
                    navController.navigate(Screen.Register.route)
                },
                loginViewModel = loginViewModel,
                modifier = Modifier.fillMaxWidth()
            )
        }
    }
}
