package com.rijai.BackendApi.repository;

import com.rijai.BackendApi.model.Dog;
import org.springframework.data.repository.CrudRepository;
import org.springframework.stereotype.Repository;

@Repository
public interface DogRepository extends CrudRepository <Dog, Long> {
}