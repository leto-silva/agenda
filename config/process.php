<?php

    session_start();
    include_once("connection.php");
    include_once("url.php");

    $data = $_POST;

    // MODIFICAÇÕES NO BANCO
    if (!empty($data)) {

        //Criar contato
        if ($data["type"]==="create"){

            $name = $data['name'];
            $phone = $data['phone'];
            $observations = $data['observations'];

            $query = "INSERT INTO contacts (name, phone, observations) values (:name, :phone, :observations)";
            
            print_r($query);
            $stmt = $conn->prepare($query);

            $stmt->bindParam(":name", $name);
            $stmt->bindParam(":phone", $phone);
            $stmt->bindParam(":observations", $observations);

            try {
                $stmt->execute();
                $_SESSION["msg"] = "Contato criado com sucesso!";
            } catch(PDOException $e){
                $error = $e->getMessage();
                echo "Erro: $error";
            }


        } else if ($data["type"]==="edit") {

            $name = $data['name'];
            $phone = $data['phone'];
            $observations = $data['observations'];
            $id = $data["id"];

            $query = "UPDATE contacts 
                    SET name = :name, phone= :phone, observations = :observations 
                    WHERE id = :id"; 

            $stmt = $conn->prepare($query);

            $stmt->bindParam(":name", $name);
            $stmt->bindParam(":phone", $phone);
            $stmt->bindParam(":observations", $observations);
            $stmt->bindParam(":id", $id);

            try {
                $stmt->execute();
                $_SESSION["msg"] = "Contato atualizado com sucesso!";
            } catch(PDOException $e){
                $error = $e->getMessage();
                echo "Erro: $error";
            }        
            
        } else if ($data["type"]==="delete"){
            
            $id = $data['id'];

            $query = "DELETE FROM contacts WHERE id = :id";

            $stmt = $conn->prepare($query);
            $stmt->bindParam(":id", $id);

            try {
                $stmt->execute();
                $_SESSION["msg"] = "Contato deletado com sucesso!";
            } catch(PDOException $e){
                $error = $e->getMessage();
                echo "Erro: $error";
            }        

        }

        // Redirect HOME
        header("LOCATION:" . $BASE_URL . "../index.php");

    } else {
        // Retorna o dado de um contato
        if(!empty($_GET['id'])){
            $id = $_GET['id'];
            $query = "SELECT * FROM contacts where id = :id";
            $stmt = $conn->prepare($query);
            $stmt->bindparam(":id", $id);
            $stmt->execute();
            $contact = $stmt->fetch();
        } else {
            $contacts = [];
            $query = "SELECT * FROM contacts";

            $stmt = $conn->prepare($query);
            $stmt->execute();
            $contacts = $stmt->fetchAll();
        }



    }








   
    





