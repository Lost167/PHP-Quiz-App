<?php

require_once 'dbconnect.php';
require_once(__DIR__ . '/../entity/User.php');


class UserAccessor {

    #private $getByIDStatementString = "select * from ShinyPokemon where pkmnID = :pkmnID";
    private $deleteStatementString = "delete from QuizAppUser where usename = :usename";
    #private $insertStatementString = "insert INTO ShinyPokemon values (:pkmnID, :pkmnName, :pkmnGame, :pkmnMethod, :pkmnEncounters, :pkmnShinyCharm, :obtained)";
    #private $updateStatementString = "update ShinyPokemon set pkmnName = :pkmnName, pkmnGame = :pkmnGame, pkmnMethod = :pkmnMethod, pkmnEncounters = :pkmnEncounters"
    #                               . ", pkmnShinyCharm = :pkmnShinyCharm, obtained = :obtained where pkmnID = :pkmnID";
    private $conn = NULL;
    #private $getByIDStatement = NULL;
    private $deleteStatement = NULL;
    #private $insertStatement = NULL;
    #private $updateStatement = NULL;

    public function getAllUsers()
    {
        $result = [];
        $stmt = null;
        try {
            $conn = connect_db();
            $stmt = $conn->prepare("select * from QuizAppUser where permissionLevel='USER'");
            $stmt->execute();
            $dbresults = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($dbresults as $r) {
                $username = $r['username'];
                $password = $r['password'];
                $obj = new User($username, $password, "USER");
                array_push($result, $obj);
            }
        } catch (Exception $e) {
            $result = [];
        } finally {
            if (!is_null($stmt)) {
                $stmt->closeCursor();
            }
        }

        return $result;
    }

    public function getAllAccounts()
    {
        $result = [];
        $stmt = null;
        try {
            $conn = connect_db();
            $stmt = $conn->prepare("select * from QuizAppUser");
            $stmt->execute();
            $dbresults = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($dbresults as $r) {
                $username = $r['username'];
                $password = $r['password'];
                $perm = $r['permissionLevel'];
                $obj = new User($username, $password, $perm);
                array_push($result, $obj);
            }
        } catch (Exception $e) {
            $result = [];
        } finally {
            if (!is_null($stmt)) {
                $stmt->closeCursor();
            }
        }

        return $result;
    }

    public function getAccountForUser($username)
    {
        $result = null;
        $stmt = null;
        try {
            $conn = connect_db();
            $stmt = $conn->prepare("select * from QuizAppUser where username = :username");
            $stmt->bindParam(":username", $username);
            $stmt->execute();
            $dbresults = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if(count($dbresults)===1) {
                $result=new User($dbresults[0]["username"], $dbresults[0]["password"], $dbresults[0]["permissionLevel"]);
            }
            
        } catch (Exception $e) {
            $result = null;
        } finally {
            if (!is_null($stmt)) {
                $stmt->closeCursor();
            }
        }

        return $result;
    }

    # Add a User
    public function addNewUser($user) {
        $success;

        $username = $user->getUsername();
        $password = $user->getPassword();
        $permissionLevel = $user->getPermissionLevel();

        try {
            $conn = connect_db();
            $stmt = $conn->prepare("insert into QuizAppUser VALUES (:username, :password, :permissionLevel)");
            $stmt->bindParam(":username", $username);
            $stmt->bindParam(":password", $password);
            $stmt->bindParam(":permissionLevel", $permissionLevel);
            $success= $stmt->execute();
        }
        catch (PDOException $e) {
            $success = false;
        }
        finally {
            if (!is_null($stmt)) {
                $stmt->closeCursor();
            }
            return $success;
        }
    }
    
    # Delete a User
    public function deleteUser($user) {
        $success;

        $username = $user->getUsername(); # Only username is needed

        try {
            $this->deleteStatement->bindParam(":username", $username);
            $success = $this->deleteStatement->execute();
        }
        catch (PDOException $e) {
            $success = false;
        }
        finally {
            if (!is_null($this->deleteStatement)) {
                $this->deleteStatement->closeCursor();
            }
            return $success;
        }
    }
}
