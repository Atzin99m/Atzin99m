package controllers;

import jakarta.persistence.*;
import javax.annotation.processing.Generated;


/**
 *
 * @author Atzin Mauricio Luna
 */

@Entity
@Table  (name="Genero")
public class controllergenero {
    @Id 
    @GeneratedValue(strategy= GenerationType.IDENTITY)
    @Column(unique=true, nullable=false )
    private long id;
    public long getId() {
        return id;
    }
    public void setId(long id) {
        this.id = id;
    }
    private String genero;
    public String getGenero() {
        return genero;
    }
    public void setGenero(String genero) {
        this.genero = genero;
    }
}
