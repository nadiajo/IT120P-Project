package com.rijai.BackendApi.service;

import com.rijai.BackendApi.model.Dog;
import com.rijai.BackendApi.repository.DogRepository;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;

import java.util.List;
import java.util.Optional;

@Service
public class DogService implements IDogService {

    @Autowired
    private DogRepository repository;

    public List<Dog> getDogs() {
        return (List<Dog>) repository.findAll();
    }

    public Dog getDog(long id) {
        Optional<Dog> optional = repository.findById(id);
        if (optional.isEmpty()) {
            return null;
        } else {
            return optional.get();
        }
    }

    public Dog addDog(Dog dog) {
        return repository.save(dog);
    }

    public Dog updateDog(long id, Dog updatedDog) {
        Optional<Dog> optional = repository.findById(id);
        if (optional.isPresent()) {
            Dog existingDog = optional.get();
            existingDog.setName(updatedDog.getName());
            existingDog.setBreed(updatedDog.getBreed());
            existingDog.setAge(updatedDog.getAge());
            existingDog.setStatus(updatedDog.getStatus());
            existingDog.setImageUrl(updatedDog.getImageUrl());
            return repository.save(existingDog);
        } else {
            return null;
        }
    }

    public void deleteDog(long id) {
        Optional<Dog> optional = repository.findById(id);
        if (optional.isPresent()) {
            repository.delete(optional.get());
        }
    }
}