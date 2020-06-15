# Task
 **Situation**

Bank users can go to a branch to cash in and/or cash out from Bank account. Several currencies are supported. There are also commission fees for both cash in and cash out.
# Commission Fees
**For Cash In**

Commission fee - 0.03% from total amount, but no more than 5.00 EUR.
**For Cash Out**

There are different commission fees for cash out for natural and legal persons.
**Natural Persons**

Default commission fee - 0.3% from cash out amount.

1000.00 EUR per week (from monday to sunday) is free of charge.

If total cash out amount is exceeded - commission is calculated only from exceeded amount (that is, for 1000.00 EUR there is still no commission fee).

This discount is applied only for first 3 cash out operations per week for each user - for forth and other operations commission is calculated by default rules (0.3%) - rule about 1000 EUR is applied only for first three cash out operations.
**Legal persons**

Commission fee - 0.3% from amount, but not less than 0.50 EUR for operation.
**Currency for Commission Fee**

Commission fee is always calculated in the currency of particular operation (for example, if you cash out `USD`, commission fee is also in `USD`).
**Rounding**

After calculating commission fee, it's rounded to the smallest currency item (for example, for `EUR` currency - cents) to upper bound (ceiled). For example, `0.023 EUR` should be rounded to `3` Euro cents.

Rounding is performed after currency conversion.
# Supported currencies

3 currencies are supported: `EUR`, `USD` and `JPY`.

When converting currencies, following conversion rates are applied: `EUR:USD` - `1:1.1497`, `EUR:JPY` - `1:129.53`
# Input data

Input data is given in CSV file. Performed operations are given in that file. In each line following data is provided:

   - operation date in format `Y-m-d`
   - user's identificator, number
   - user's type, one of `natural` or `legal`
   -  operation type, one of `cash_in` or `cash_out`
   -  operation amount (for example `2.12` or `3`)
   -  operation currency, one of `EUR`, `USD`, `JPY`

All operations are ordered by their date ascendingly.
# Expected Result

As a single argument program must accept a path to the input file.

Program must output result to `stdout`.

Result - calculated commission fees for each operation. In each line only final calculated commission fee must be provided without currency.

# SYSTEM RUNNING

To run this system you should print and run in CLI:
`php script.php "file.csv"` . Where `"file.csv"` is
your file with data consisting of:
- Date example: `2014-12-31`;
- User id example: `1`;
- User type example: `legal`;
- Operation type example `cash_out`;
- Amount of operation money example :`100.00`;
- Currency example: `EUR`.

To run system immediately, type: `php script.php input.csv`. `input.csv` is file created by me with examples.

# SYSTEM TESTING

To test the system print and run in CLI:
`composer run test`.# bank-master
