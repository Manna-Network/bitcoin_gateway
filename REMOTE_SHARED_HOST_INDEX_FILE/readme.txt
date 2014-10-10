### Read Me ###
#
# This index_OS.php page can be renamed to anything you wish.
# It is the sole page needing to be installed on the shared server
# Its sole purpose is to communicate via curl with the actual Bitcoin server (Testnet in this default state).
# The communication will send the account name and whether it is TestNet or real Bitcoin via the curl call
# There is nothing "pushing" changes of the transaction status back to this page from the Bitcoin server so this page needs constant refreshing to discover the real time status of the transaction and to cause the account to be credited
# 
# A new, first time user means they will both have an account created for them as well as an address under that account. Subsequent uses by the same user will re-use the same account and will create a new, unique address to submit a payment to.
# An address can receive any number of deposits to it. The script and accounting works best when the user only makes one deposit to one address. The script will recover the funds when the user ignores the warnings and makes multiple deposits to the same address but the reporting may be confusing to them.
# The current settings are that the user will get credited upon the first confirmation provided by the network (in Bitcoin the average time that takes is less than ten minutes).
# Another new address will be only be created after the previous transaction has received 3 confirmations.
# Those two parameters for crediting and delivering a new address are configurable by the programmer 
# Crediting at zero confirmations can expose the site to the "transaction malleability" issue. 
# The crediting is done through an editable function named credit_BB_account located in the process_testnet_class.php page that has a $file variable containing the url in the curl function to your processing file on your shared server. Change that file to where your own crediting and processing page is located. The script will run that credit_BB_account function after 3 confirmations.


