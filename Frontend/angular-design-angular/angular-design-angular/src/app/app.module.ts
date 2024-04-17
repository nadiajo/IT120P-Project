import { NgModule, CUSTOM_ELEMENTS_SCHEMA } from '@angular/core'
import { BrowserModule } from '@angular/platform-browser'
import { HttpClientModule } from '@angular/common/http'; 
import { FormsModule } from '@angular/forms';

import { AppComponent } from './app.component'
import { RouterModule, Routes } from '@angular/router';
import { About } from './pages/about/about.component';
import { AdminAddDog } from './pages/admin-add-dog/admin-add-dog.component';
import { AdminHome } from './pages/admin-home/admin-home.component';
import { AdminDogView } from './pages/admin-dog-view/admin-dog-view.component';
import { AdminLogin } from './pages/admin-login/admin-login.component';
import { AdminProfile } from './pages/admin-profile/admin-profile.component';
import { AdminUpdateDog } from './pages/admin-update-dog/admin-update-dog.component';
import { DogProfile } from './pages/dog-profile/dog-profile.component';
import { Homepage } from './pages/homepage/homepage.component';
import { LoginChooser } from './pages/login-chooser/login-chooser.component';
import { NonUserErrorAdoption } from './pages/non-user-error-adoption/non-user-error-adoption.component';
import { NotFound } from './pages/not-found/not-found.component';
import { UserAbout } from './pages/user-about/user-about.component';
import { UserAdoption } from './pages/user-adoption/user-adoption.component';
import { UserHome } from './pages/user-home/user-home.component';
import { UserLogin } from './pages/user-login/user-login.component';
import { UserProfile } from './pages/user-profile/user-profile.component';

const routes: Routes = [
  { path: '', redirectTo: '/homepage', pathMatch: 'full' },
  { path: 'about', component: About },
  { path: 'admin-add-dog', component: AdminAddDog },
  { path: 'admin-dog-view', component: AdminDogView },
  { path: 'admin-home', component: AdminHome },
  { path: 'admin-login', component: AdminLogin },
  { path: 'admin-profile', component: AdminProfile },
  { path: 'admin-update-dog/:id', component: AdminUpdateDog },
  { path: 'dog-profile', component: DogProfile },
  { path: 'homepage', component: Homepage },
  { path: 'login-chooser', component: LoginChooser },
  { path: 'non-user-error-adoption', component: NonUserErrorAdoption },
  { path: 'not-found', component: NotFound },
  { path: 'user-about', component: UserAbout },
  { path: 'user-adoption', component: UserAdoption },
  { path: 'user-home', component: UserHome },
  { path: 'user-login', component: UserLogin },
  { path: 'user-profile', component: UserProfile },
  { path: '**', redirectTo: 'not-found' },
];

@NgModule({
  declarations: [
    About,
    AppComponent,
    AdminAddDog,
    AdminHome,
    AdminDogView,
    AdminLogin,
    AdminProfile,
    AdminUpdateDog,
    DogProfile,
    Homepage,
    LoginChooser,
    NonUserErrorAdoption,
    NotFound,
    UserAbout,
    UserAdoption,
    UserHome,
    UserLogin,
    UserProfile
    // Add any other components here
  ],
  imports: [
    FormsModule,
    BrowserModule,
    HttpClientModule,
    RouterModule.forRoot(routes)
  ],
  providers: [],
  bootstrap: [AppComponent],
  schemas: [CUSTOM_ELEMENTS_SCHEMA],
})
export class AppModule {}
