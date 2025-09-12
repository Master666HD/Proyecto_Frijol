package com.example.appfrijol.navigation



import androidx.compose.foundation.background
import androidx.compose.foundation.layout.Box
import androidx.compose.foundation.layout.padding
import androidx.compose.foundation.layout.size
import androidx.compose.foundation.shape.CircleShape
import androidx.compose.material3.Icon
import androidx.compose.material3.MaterialTheme
import androidx.compose.material3.NavigationBar
import androidx.compose.material3.NavigationBarItem
import androidx.compose.material3.Scaffold
import androidx.compose.material3.Text
import androidx.compose.runtime.Composable
import androidx.compose.runtime.collectAsState
import androidx.compose.runtime.getValue
import androidx.compose.ui.Alignment
import androidx.compose.ui.Modifier
import androidx.compose.ui.text.style.TextOverflow
import androidx.compose.ui.unit.dp
import androidx.compose.ui.unit.sp
import androidx.hilt.navigation.compose.hiltViewModel
import androidx.navigation.NavHostController
import androidx.navigation.compose.NavHost
import androidx.navigation.compose.composable
import androidx.navigation.compose.currentBackStackEntryAsState
import androidx.navigation.compose.rememberNavController
import com.example.appfrijol.presentation.home.HomeScreen
import com.example.appfrijol.presentation.home.HomeViewModel
import com.example.appfrijol.presentation.learning.LearningScreen
import com.example.appfrijol.presentation.more.MoreScreen
import com.example.appfrijol.presentation.profile.ProfileScreen
import com.example.appfrijol.presentation.result.ResultScreen
import com.example.appfrijol.presentation.session.SessionViewModel

@Composable
fun HomeWithBottomNav(
    sessionViewModel: SessionViewModel,
    onLogout: () -> Unit,
    navController: NavHostController = rememberNavController()
) {
    val items = listOf(
        BottomNavItem.Results,
        BottomNavItem.Learning,
        BottomNavItem.Home,   // 🔹 centro
        BottomNavItem.Profile,
        BottomNavItem.More
    )


    Scaffold(
        topBar = {
            MyAppTopAppBar()
        },
        bottomBar = {
            NavigationBar(
                containerColor = MaterialTheme.colorScheme.surface,
                tonalElevation = 8.dp
            ) {
                val navBackStackEntry by navController.currentBackStackEntryAsState()
                val currentRoute = navBackStackEntry?.destination?.route

                items.forEach { item ->
                    NavigationBarItem(
                        selected = currentRoute == item.route,
                        onClick = {
                            navController.navigate(item.route) {
                                popUpTo(navController.graph.startDestinationId) { saveState = true }
                                launchSingleTop = true
                                restoreState = true
                            }
                        },
                        icon = {
                            if (item == BottomNavItem.Home) {
                                // 🔹 centro resaltado
                                Box(
                                    modifier = Modifier
                                        .size(56.dp)
                                        .background(
                                            color = MaterialTheme.colorScheme.primary,
                                            shape = CircleShape
                                        ),
                                    contentAlignment = Alignment.Center
                                ) {
                                    Icon(
                                        imageVector = item.icon,
                                        contentDescription = item.label,
                                        tint = MaterialTheme.colorScheme.onPrimary
                                    )
                                }
                            } else {
                                Icon(
                                    imageVector = item.icon,
                                    contentDescription = item.label,
                                    tint = if (currentRoute == item.route)
                                        MaterialTheme.colorScheme.primary
                                    else
                                        MaterialTheme.colorScheme.onSurface
                                )
                            }
                        },
                        label = {
                            if (item.label.isNotEmpty()) {
                                Text(
                                    text = item.label,
                                    fontSize = 9.sp, // tamaño reducido para textos largos
                                    maxLines = 1,
                                    overflow = TextOverflow.Ellipsis
                                )
                            }
                        }
                    )
                }
            }
        }
    ) { innerPadding ->
        NavHost(
            navController = navController,
            startDestination = BottomNavItem.Home.route,
            modifier = Modifier.padding(innerPadding)
        ) {
            composable(BottomNavItem.Home.route) {
                val homeViewModel: HomeViewModel = hiltViewModel()
                val sessionViewModel: SessionViewModel = hiltViewModel()

                val userName by sessionViewModel.userName.collectAsState(initial = "Usuario")

                HomeScreen(
                    viewModel = homeViewModel,
                    userName = userName
                )
            }



            composable(BottomNavItem.Results.route) { ResultScreen() }
            composable(BottomNavItem.Learning.route) { LearningScreen() }
            composable(BottomNavItem.Profile.route) { ProfileScreen(onLogout) }
            composable(BottomNavItem.More.route) { MoreScreen() }
        }
    }
}

