package com.rijai.BackendApi.service;

import com.rijai.BackendApi.model.User;

public interface IUserService {
    User findByUsername(String username);
    User saveUser(User user);
    User saveAdmin(User admin);
}