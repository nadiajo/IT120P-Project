package com.rijai.BackendApi.controller;

import com.rijai.BackendApi.model.User;
// import org.springframework.beans.factory.annotation.Autowired;
// import org.springframework.security.authentication.AuthenticationManager;
// import org.springframework.security.authentication.BadCredentialsException;
// import org.springframework.security.authentication.UsernamePasswordAuthenticationToken;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.*;

@CrossOrigin(origins = "http://localhost:4200")
@RestController
@RequestMapping("/api")
public class LoginController {

    // @Autowired
    // private AuthenticationManager authenticationManager;

    // @Autowired
    // private CustomUserDetailsService userDetailsService;

    @PostMapping("/login")
    public ResponseEntity<?> authenticate(@RequestBody User user) {
        // try {
        //     authenticationManager.authenticate(
        //             new UsernamePasswordAuthenticationToken(
        //                     user.getUsername(),
        //                     user.getPassword()
        //             )
        //     );
        return ResponseEntity.ok("Login bypassed, no authentication needed");
        // } catch (BadCredentialsException e) {
        //     return ResponseEntity.status(401).body("Invalid Username or Password");
        // }
    }
}