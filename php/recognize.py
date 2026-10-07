import sys
import face_recognition

def compare_faces(stored_image_path, captured_image_path):
    try:
        # Load the stored image and captured image
        stored_image = face_recognition.load_image_file(stored_image_path)
        captured_image = face_recognition.load_image_file(captured_image_path)
        
        # Get the face encodings for each image
        # We assume there is at least one face in each image
        stored_encodings = face_recognition.face_encodings(stored_image)
        captured_encodings = face_recognition.face_encodings(captured_image)
        
        if not stored_encodings:
            print("Error: No face found in the stored image.")
            return False
            
        if not captured_encodings:
            print("Error: No face found in the captured image.")
            return False
            
        # Compare the first face found in both images
        match = face_recognition.compare_faces([stored_encodings[0]], captured_encodings[0])[0]
        
        if match:
            print("Match")
            return True
        else:
            print("No Match")
            return False
            
    except Exception as e:
        print(f"Error processing images: {str(e)}")
        return False

if __name__ == "__main__":
    if len(sys.argv) != 3:
        print("Usage: python recognize.py <stored_image_path> <captured_image_path>")
        sys.exit(1)
        
    stored_path = sys.argv[1]
    captured_path = sys.argv[2]
    
    compare_faces(stored_path, captured_path)
