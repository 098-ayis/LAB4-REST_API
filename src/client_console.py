import requests

# Color Definitions
GREEN = "\033[92m"
YELLOW = "\033[93m"
RED = "\033[91m"
BLUE = "\033[94m"
CYAN = "\033[96m"
PINK = "\033[95m"
BOLD = "\033[1m"
RESET = "\033[0m"

# The Bridge: This points to your PHP files in XAMPP
path = "http://localhost/LAB4-REST_API/src/"

def welcome_screen():
    print(f"\n\n\n{GREEN}{BOLD}")
    print(r"""      .--.      .--.    .-''-.    .---.        _______      ,-----.    ,---.    ,---.    .-''-.      ,---------.    ,-----.         """)
    print(r"""      |  |_     |  |  .'_ _   \   | ,_|       /   __  \   .'  .-,  '.  |    \  /    |  .'_ _   \     \          \ .'  .-,  '.       """)
    print(r"""      | _( )_   |  | / ( ` )   ',-./  )      | ,_/  \__) / ,-.|  \ _ \ |  ,  \/  ,  | / ( ` )   '     `--.  ,---'/ ,-.|  \ _ \      """)
    print(r"""      |(_ o _)  |  |. (_ o _)  |\  '_ '`)  ,-./  )      ;  \  '_ /  | :|  |\_   /|  |. (_ o _)  |        |   \  ;  \  '_ /  | :     """)
    print(r"""      | (_,_) \ |  ||  (_,_)___| > (_)  )  \  '_ '`)    |  _`,/ \ _/  ||  _( )_/ |  ||  (_,_)___|        :_ _:  |  _`,/ \ _/  |     """)
    print(r"""      |  |/    \|  |'  \   .---.(  .  .-'   > (_)  )  __: (  '\_/ \   ;| (_ o _) |  |'  \   .---.        (_I_)  : (  '\_/ \   ;     """)
    print(r"""      |  '  /\  `  | \  `-'    / `-'`-'|___(  .  .-'_/  )\ `"/  \  ) / |  (_,_)  |  | \  `-'    /       (_(=)_)  \ `"/  \  ) /      """)
    print(r"""      |    /  \    |  \       /   |        \`-'`-'     /  '. \_/``".'  |  |      |  |  \       /         (_I_)    '. \_/``".'       """)
    print(r"""      `---'    `---`   `'-..-'    `--------`  `._____.'     '-----'    '--'      '--'   `'-..-'          '---'      '-----'         """)
    print(r"""       ________   .---.       .-''-.    ___    _ .-------.        _______   .---.  .---.    ____       .-'''-.     .-''-.           """)
    print(r"""      |        |  | ,_|     .'_ _   \ .'   |  | ||  _ _   \      /   __  \  |   |  |_ _|  .'  __ `.   / _     \  .'_ _   \          """)
    print(r"""      |   .----',-./  )    / ( ` )   '|   .'  | || ( ' )  |     | ,_/  \__) |   |  ( ' ) /   '  \  \ (`' )/`--' / ( ` )   '         """)
    print(r"""      |  _|____ \  '_ '`) . (_ o _)  |.'  '_  | ||(_ o _) /   ,-./  )       |   '-(_{;}_)|___|  /  |(_ o _).   . (_ o _)  |         """)
    print(r"""      |_( )_   | > (_)  ) |  (_,_)___|'   ( \.-.|| (_,_).' __ \  '_ '`)     |      (_,_)    _.-`   | (_,_). '. |  (_,_)___|         """)
    print(r"""      (_ o._)__|(  .  .-' '  \   .---.' (`. _` /||  |\ \  |  | > (_)  )  __ | _ _--.   | .'   _    |.---.  \  :'  \   .---.         """)
    print(r"""      |(_,_)     `-'`-'|___\  `-'    /| (_ (_) _)|  | \ `'   /(  .  .-'_/  )|( ' ) |   | |  _( )_  |\    `-'  | \  `-'    /         """)
    print(r"""      |   |       |        \\       /  \ /  . \ /|  |  \    /  `-'`-'     / (_{;}_)|   | \ (_ o _) / \       /   \       /          """)
    print(fr"""      '---'       `--------` `'-..-'    ``-'`-'' ''-'   `'-'     `._____.'  '(_,_) '---'  '.(_,_).'   `-...-'     `'-..-'{RESET}   """)
    print(f"\n\n{PINK}                                                 ~ Your Local Digital Flower Shop ~       {RESET}\n")
    
   

    
def handle_api_response(response):
    """
    Translates the JSON from PHP into Python.
    Handles the Multi-Status Responses (200, 401, 404, etc.)
    """
    try:
        data = response.json()
        status_code = data.get('code')
        message = data.get('message')

        if status_code in [200, 201]:
            print(f"\nSuccess: {message}")
            return data
        else:
            print(f"\nError {status_code}: {message}")
            return None
    except:
        # If PHP crashes, this shows you the actual error instead of just failing
        print(f"\nDEBUG (Raw Server Output): {response.text}")
        return None

def login():
    print(f"\n\n\n{PINK}******************************")
    print(f"      FLEURCHASE LOGIN       ")
    print(f"******************************{RESET}")
    email = input("Email: ")
    password = input("Password: ")
    
    payload = {'user_email': email, 'user_pass': password}
    response = requests.post(f"{path}login.php", data=payload)
    return handle_api_response(response)

def register():
    print(f"\n\n\n{PINK}******************************")
    print(f"      REGISTER ACCOUNT       ")
    print(f"******************************{RESET}")
    first_name = input("First Name: ")
    last_name  = input("Last Name: ")
    contact    = input("Contact Number: ")
    email      = input("Email Address: ")
    password   = input("Password: ")
    
    payload = {
        'user_email': email,   
        'user_pass': password,  
        'first_name': first_name,
        'last_name': last_name,
        'contact': contact
    }
    
    response = requests.post(f"{path}register.php", data=payload)
    data = handle_api_response(response)
    
    if data:
        print(f"\n----------------------------------------")
        print(f" {BOLD}{GREEN}         Account created!{RESET}")
        print(f"----------------------------------------")
        input("\nPress Enter to return to the main menu...")

def show_products():
    print(f"\n\n\n{PINK}******************************")
    print(f"      CURRENT INVENTORY       ")
    print(f"******************************{RESET}")
    response = requests.get(f"{path}view_from_inventory.php")
    data = handle_api_response(response)
    
    if data and 'inventory' in data:
        header = f"| {'ID':<4} | {'Flower Name':<20} | {'Stock':<6} | {'Price':<8} | {'Date Arrived':<12} | {'Life':<8} |"
        line = "-" * len(header)
        
        print(line)
        print(header)
        print(line)
        
        for item in data['inventory']:
            print(f"| {item['inventory_id']:<4} | "
                  f"{item['flower_name']:<20} | "
                  f"{item['stock']:<6} | "
                  f"P{float(item['base_price_per_stem']):<7.2f} | "
                  f"{item['date_arrived']:<12} | "
                  f"{item['shelf_life']:<3} days |")
        
        print(line)
    elif data:
        print("Inventory is currently empty.")

def add_product():
    print(f"\n\n\n{PINK}******************************")
    print(f"        ADD NEW PRODUCT       ")
    print(f"******************************{RESET}")
    name = input("Flower Name: ")
    image = input("Image Filename: ") 
    stock = input("Quantity: ")
    price = input("Price per stem: ")
    date = input("Date Arrived (YYYY-MM-DD): ")
    life = input("Shelf Life (days): ")
    
    payload = {
        'flower_name': name,
        'flower_image': image, 
        'stock': stock,
        'base_price_per_stem': price,
        'date_arrived': date,
        'shelf_life': life
    }
    
    response = requests.post(f"{path}add_to_inventory.php", data=payload)
    handle_api_response(response)

def update_product():
    print(f"\n\n\n{PINK}******************************")
    print(f"        UPDATE PRODUCT       ")
    print(f"******************************{RESET}")
    item_id = input("Enter Product ID to update: ")
    name = input("Flower Name: ")
    image = input("Image Filename: ")
    new_stock = input("Enter New Stock Quantity: ")
    price_per_stem = input("Price per stem: ")
    date = input("Date Arrived (YYYY-MM-DD): ")
    life = input("Shelf Life (days): ")
    
    payload = {
        'inventory_id': item_id,
        'flower_name': name,
        'flower_image': image,
        'stock': new_stock,
        'base_price_per_stem': price_per_stem,
        'date_arrived': date,
        'shelf_life': life
    }
    
    response = requests.put(f"{path}update_inventory.php", json=payload)
    handle_api_response(response)

def delete_product():
    print("\n--- DELETE PRODUCT ---")
    item_id = input("Enter Product ID to delete: ")
    
    payload = {'inventory_id': item_id}
    
    response = requests.delete(f"{path}delete_from_inventory.php", json=payload)
    handle_api_response(response)

# --- MAIN EXECUTION BLOCK ---
if __name__ == "__main__":
    while True:
        welcome_screen()
        print("                                                              1. Login")
        print("                                                              2. Register")
        print("                                                              3. Exit")
        
        start_choice = input(f"\nSelect (1-3): ")

        if start_choice == '1':
            user_session = login()
            if user_session:
                # Proceed to Admin Menu
                while True:
                    print(f"\n{GREEN}******************************")
                    print(f"      FLEURCHASE ADMIN       ")
                    print(f"******************************{RESET}")
                    print("\n1. View Inventory")
                    print("2. Add Product")
                    print("3. Update Product")
                    print("4. Delete Product")
                    print("5. Logout")
                    
                    choice = input("\nSelect Option (1-5): ")
                    
                    if choice == '1':
                        show_products()
                    elif choice == '2':
                        add_product()
                    elif choice == '3':
                        update_product()
                    elif choice == '4':
                        delete_product()
                    elif choice == '5':
                        print("Logging out...")
                        break
                    else:
                        print(f"{RED}Invalid choice.{RESET}")
        
        elif start_choice == '2':
            register()
        elif start_choice == '3':
            break