
const MongoClient = require('mongodb').MongoClient;
const uri = "mongodb+srv://dbAdmin:<Axelsnow1973>@demolgame.fiew4.mongodb.net/<DeMolGame>?retryWrites=true&w=majority";
const client = new MongoClient(uri, { useNewUrlParser: true, useUnifiedTopology: true });

client.connect(err => {
  const collection = client.db("test").collection("devices");
  // perform actions on the collection object
  client.close();
});

const fetch = require('node-fetch');
const express = require('express');
const app = express();


(async() => {
    try {   
// connect to the MongoDB cluster
    await client.connect();
    // make the appropriate DB calls
    ...
    } catch (e) {
        console.error(e);
    } finally {
        await client.close();
}
});
