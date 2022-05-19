# How to setup

## Uploading and establishing a database connection

- Upload all files to a web host service.
- Create a MySQL Database and import the structure from ```./db/db_structure.sql```.
- Enter the database credentials in the file ```./db/db_info.php```.

## Configuration

### Settings

In the file ```./includes/config.php``` you have all kinds of settings like when the season starts/ends, on what day and hour to vote etc...

There are award id's hardcoded in the config if you want to change those.

```Note: Award images have to have the same name as their id number in the database. As seen in the example files.```

### Adding candidates

- When adding candidates to the database in ```table_Candidates``` keep in mind to use the same firstname (case sensitive) as the image you have for that candidate in ```./img/candidates/```.
- Candidate images have to be the same size as the given example images.

```Note: Keep the unknown.jpg image as this is a placeholder in case there is no image.```

### Admin panel

You can give a user admin rights by changing the admin value in ```table_Users``` from a 0 to a 1.
This grants the user the ability to go to the admin panel. The admin panel itself is not completely finished due to a sudden ending to the project. Therefore it can only do the essential task which is setting a candidates status to ```Out```. 
All it takes is to navigate to the candidates and click the candidate that has to leave that episode and click the 'Set out' button.