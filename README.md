# VATSIM Statistics
This is a simple, independent VATSIM statistics system. The system makes use of the [VATSIM Data Handler OOP class](https://github.com/KiloSierraCharlie/VATSIM-Data-Handler), and stores statistics in .json files.

### Requirements

 - VATSIM Data Handler (V1.2)
 - file_get_contents
 - CRON Accessibility

### How to use
You may want to edit the *index.php* to better suit your design of website. If you're familiar with PHP, feel free to have a play with the loop, although make sure you're familiar with how the data is structured.

Make sure you set the *fetch.php* to run every minute (* * * * *), in order to gather data.

### Data structure

    {
      "1298134": {
        "cid": "1298134",
        "realname": "Kieran Samuel Cross",
        "accountedTime": 123456789,
        "positions": {
          "LCRA_TWR": [
            {
              "logon_time": 1523561744,
              "logoff_time": 1523563398
            },
            {
              "logon_time": 1523564118,
              "logoff_time": 1523565778
            }
          ],
          "LCRA_ATIS": [
            {
              "logon_time": 1523561744,
              "logoff_time": 1523563398
            }
          ]
        }
      }
    }