# 2Solar API Wrapper

This wrapper communicates with the 2Solar API. This wrapper keeps the customer informed with mail. The content of the mail depends on the status of a quote in the 2Solar API.

This wrapper checks the status and ensures that the correct emails are sent and not duplicates.

Exp. mailing depending on status:

| Status ID | Mail                              |
|-----------|-----------------------------------|
| 105270    | Thank you for your request (lead) |
| 116591    | Your Quote                        | 
| 156952    | Appointment scheduled             |
| 105824    | Assembly                          |
| 105895    | Completed, ready to ship          |
| 116659    | Feedback                          |
| 105265    | Send first invoice                |
| 116789    | Delivery Complete, 30 days later  |


[Zonnepanelen-API-Diagram.pdf](docs/Zonnepanelen-API-Diagram.pdf)