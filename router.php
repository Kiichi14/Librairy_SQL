<?php

include 'db.php';
include 'autoloader.php'; 

class Router {

    public function getRoute() {

        //Instance des class
        $book = new Book;
        $users = new User;
        $library = new Library;
        $wishlist = new Wishlist;
        $profil = new Profil;
        $rate = new Rate;
        $author = new Author;
//-------------------------------Route GET--------------------------------------------------
/**
 * Books
 */     if( $_SERVER["REQUEST_METHOD"]=="GET"){
            // Route GET de tout les livres
            if(isset($_GET['index'])) {
                $book->getAllBook();
            }

            //ROute GET de 1 LIvre en Particulier
            if(isset($_GET['book']) && isset($_GET['id']) && isset($_GET['search'])) {
                $id = $_GET['id'];
                $book->getBookById($id);
            }
            //Route GET Livre par auteur
            if(isset($_GET['book']) && isset($_GET['author']) && isset($_GET['search'])){
                $author = $_GET['author'];
                $book->getBookByAuthor($author);
            }
            //Route GET Livre par Catégorie
            if(isset($_GET['book']) && isset($_GET['category']) && isset($_GET['search'])){
                $category = $_GET['category'];
                $book->getBookByCategory($category);
            }
            //Route GET Livre par Titre
            if(isset($_GET['book']) && isset($_GET['title']) && isset($_GET['search'])) {
                $title = $_GET['title'];
                $book->getBookByTitle($title);
            }
            //Route GET Books par Editeur
            if(isset($_GET['book']) && isset($_GET['editor']) && isset($_GET['search'])) {
                $editor = $_GET['editor'];
                $book->getBookByEditor($editor);
            }
            /**
            * User
            */   
            //Route de touts les user
            if(isset($_GET['user']) && empty($_GET['id'])) {
                $users->getAllUser();
            }

            // Route pour un seul user
            if(isset($_GET['user']) && isset($_GET['id'])) {
                $id = $_GET['id'];
                $users->getUserById($id);
            }
            /**
            * Librairie
            */ 
            //Route GET librairie User avec statut de lecture
            if(isset($_GET['library']) && isset($_GET['id']) && isset($_GET['name'])) {
                $id = $_GET['id'];
                $name = $_GET['name'];
                $library->getLibrary($id, $name);
            }
            /**
            * Wishlist
            */     
            if(isset($_GET['wishlist']) && isset($_GET['id']) && isset($_GET['name'])) {
                $id = $_GET['id'];
                $name = $_GET['name'];
                $wishlist->getWishlist($id, $name);
            }
            /**
            * Profile
            */ 
            //Route Get nombre de livre de l'utilisateur avec statut de lecture
            if(isset($_GET['profil']) && isset($_GET['id'])) {
                $id = $_GET['id'];
                $profil->getStats($id);
            }
            /**
            * Comments, Note
            */ 
            if(isset($_GET['comment']) && isset($_GET['id']) && $_GET['book']) {
                $id = $_GET['id'];
                $book = $_GET['book'];
                $rate->getComments($id, $book);
            }
            /**
            * Author Stats
            */         
            if(isset($_GET['author']) && isset($_GET['name'])) {
                $name = $_GET['name'];
                $author->getBookStats($name);
            }
        }
        
 //---------------------------------------Route POST----------------------------------------------
        if($_SERVER["REQUEST_METHOD"]=="POST") {
            /**
             * Books
             */
                    //Route ajout de livre
                    if(isset($_GET['submit']) && isset($_GET['book'])) {
                        if(!empty($_POST['title']) && !empty($_POST['category']) && !empty($_POST['resume']) && !empty($_POST['author']) && !empty($_POST['editeur'])) {
                            $title=$_POST["title"];
                            $category = $_POST["category"];
                            $resume=$_POST["resume"];
                            $author = $_POST["author"];
                            $editor = $_POST["editeur"];
                            $book->insertBook($title, $category, $resume, $author, $editor);
                        }
                    }

                    //Route ajout livre edition
                    if(isset($_GET['submit']) && isset($_GET['book_edition'])) {
                        if(!empty($_POST['edition']) && !empty($_POST['user'])) {
                            $edition = $_POST['edition'];
                            $user = $_POST['user'];
                            $library->addToLibrary($edition, $user);
                        }
                    }

            /**
             * Comments, Note
             */     
                    //Route ajout de commentaire
                    if(isset($_GET['submit']) && isset($_GET['comment'])) {
                        if(!empty($_POST['comment']) && !empty($_POST['rate']) && !empty($_POST['book']) && !empty($_POST['user'])) {
                            $comment = $_POST["comment"];
                            $note = $_POST["rate"];
                            $book = $_POST["book"];
                            $user = $_POST['user'];
                            $rate->postRate($comment, $note, $book, $user);
                        }
                    }
            /**
             * Wishlist
             */
                    if(isset($_GET['submit']) && isset($_GET['wishlist'])) {
                        if(!empty($_POST['id']) && !empty($_POST['edition'])) {
                            $id = $_POST['id'];
                            $edition = $_POST['edition'];
                            $wishlist->addToWishlist($id, $edition);
                        }
                    }
            //------------------------------------------Route UPDATE--------------------------------------
            /**
             * Books, Genre
             */
                    //Update du genre d'un livre
                    if(isset($_GET['update'])) {
                        if(!empty($_POST['id']) && !empty($_POST['genre'])) {
                            $id = $_POST['id'];
                            $genre = $_POST['genre'];
                            $book->updateCategory($genre, $id);
                        }
                    }
                    //Retrait d'une catégorie d'un livre
                    if(isset($_GET['delete']) && isset($_GET['category']) && isset($_GET['book'])) {
                        $id = $_GET['book'];
                        $book->deleteCategory($id);
                    }
            /**
             * Note
             */
                    if(isset($_GET['update']) && isset($_GET['note'])) {
                        if(!empty($_POST['rate']) && !empty($_POST['id']) && !empty($_POST['user'])) {
                            $note = $_POST['rate'];
                            $id = $_POST['id'];
                            $user = $_POST['user'];
                            $rate->updateRate($note, $id, $user);
                        }
                    }
}

//-------------------------------------------ROUTE DELETE-------------------------------------

        if($_SERVER["REQUEST_METHOD"]=="DELETE") {

            if(isset($_GET['delete']) && isset($_GET['librairy']) && isset($_GET['user']) && isset($_GET['id'])) {
                $user = $_GET['user'];
                 $id = $_GET['id'];
                $library->deleteBook($user, $id);
            }    

        }    
    }
}
?>