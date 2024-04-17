package com.rijai.BackendApi.controller;

import com.rijai.BackendApi.model.Dog;
import com.rijai.BackendApi.service.IDogService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.*;

import java.util.List;

@CrossOrigin(origins = "http://localhost:4200")
@RestController
public class DogController {

    @Autowired
    private IDogService dogService;

    @RequestMapping("/api/dogs")
    public List<Dog> findDogs(){
        return dogService.getDogs();
    }

    @RequestMapping(value = "/api/show-dog/{id}")
    public Dog showDog(@PathVariable long id) {
        return dogService.getDog(id);
    }

    @RequestMapping(value="/api/add-dog", method= RequestMethod.POST)
    public Dog addDogSubmit(@RequestBody Dog dog) {
        return dogService.addDog(dog);
    }

    @RequestMapping(value="/api/update-dog/{id}", method=RequestMethod.PUT)
    public Dog updateDog(@PathVariable long id, @RequestBody Dog updatedDog) {
        return dogService.updateDog(id, updatedDog);
    }

    @RequestMapping(value="/api/delete-dog/{id}", method=RequestMethod.DELETE)
    public void deleteDog(@PathVariable long id) {
        dogService.deleteDog(id);
    }

    @PostMapping("/api/interest/{id}")
    public ResponseEntity<String> expressInterest(@PathVariable long id) {
        Dog dog = dogService.getDog(id);
        if (dog == null) {
            return ResponseEntity.badRequest().body("Dog with ID " + id + " is not found.");
        }
        dog.setStatus("Reserved");
        dogService.updateDog(id, dog);
        return ResponseEntity.ok("{\"message\": \"Interest in adopting dog " + dog.getName() + " has been notified, status set to Reserved.\"}");
    }
}