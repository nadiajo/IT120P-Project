package com.rijai.BackendApi.service;

import com.rijai.BackendApi.model.Dog;

import java.util.List;

public interface IDogService {
    List<Dog> getDogs();
    Dog addDog(Dog dog);
    Dog updateDog(long id, Dog updatedDog);
    Dog getDog(long id);
    void deleteDog(long id);
}