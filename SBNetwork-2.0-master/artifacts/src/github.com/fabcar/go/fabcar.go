package main

import (
	"bytes"
	"encoding/json"
	"fmt"
	"strconv"
	"time"

	"github.com/hyperledger/fabric-chaincode-go/shim"
	sc "github.com/hyperledger/fabric-protos-go/peer"
	"github.com/hyperledger/fabric/common/flogging"

	"github.com/hyperledger/fabric-chaincode-go/pkg/cid"
)

// SmartContract Define the Smart Contract structure
type SmartContract struct {
}

// bag :  Define the bag structure, with 4 properties.  Structure tags are used by encoding/json library
type Bag struct {
	Id                 string `json:"id"`
	Generated_Date     string `json:"Generated_Date"`
	Status             string `json:"Status"`
	Assigned_bloodBank string `json:"Assigned_bloodBank"`
}

type Screening struct {
	Id          string `json:"Id"`
	Test_date   string `json:"Test_date"`
	HBV         string `json:"HBV"`
	HCV         string `json:"HCV"`
	HIV         string `json:"HIV"`
	Syphilis    string `json:"Syphilis"`
	Malaria     string `json:"Malaria"`
	PVC         string `json:"PVC"`
	BloodType   string `json:"BloodType"`
	Rhesus      string `json:"Rhesus"`
	Approved_by string `json:"Approved_by"`
	Agent_Id    string `json:"Agent_Id"`
	Bag_id      string `json:"Bag_id"`
}

type Organizations struct {
	Id                string `json:"Id"`
	OrganizationsName string `json:"OrganizationsName"`
	Phone             string `json:"Phone"`
	Email             string `json:"Email"`
	Address           string `json:"Address"`
	City              string `json:"City"`
	Latitude          string `json:"Latitude"`
	Longitude         string `json:"Longitude"`
	Category          string `json:"Category"`
	Status            string `json:"Status"`
	State             string `json:"State"`
	Country           string `json:"Country"`
}

type User struct {
	Id               string `json:"mId"`
	FirstName        string `json:"FirstName"`
	LastName         string `json:"LastName "`
	Phone            string `json:"Phone"`
	Email            string `json:"Email"`
	Privilege        string `json:"Privilege"`
	Status           string `json:"Status"`
	Organizations_id string `json:"Organizations_id"`
}

type RequestScreening struct {
	Id           string `json:"Id"`
	Screener_id  string `json:"Screener_id"`
	Donated_date string `json:"Donated_date"`
	Donor_id     string `json:"Donor_id"`
	Status       string `json:"Status"`
	Bag_id       string `json:"Bag_id"`
}

type Movement struct {
	Id             string `json:"Id"`
	Start_location string `json:"Start_location"`
	End_location   string `json:"End_location"`
	Start_time     string `json:"Start_time"`
	End_time       string `json:"End_time"`
	Temperature    string `json:"Temperature"`
	Bag_id         string `json:"Bag_id"`
}

type Destroyed struct {
	Id          string `json:"Id"`
	DestoryDate string `json:"DestoryDate"`
	Reason      string `json:"Reason"`
	Proof       string `json:"Proof"`
	Bag_id      string `json:"Bag_id"`
}

// Init ;  Method for initializing smart contract
func (s *SmartContract) Init(APIstub shim.ChaincodeStubInterface) sc.Response {
	return shim.Success(nil)
}

var logger = flogging.MustGetLogger("fabsmart_cc")

// Invoke :  Method for INVOKING smart contract
func (s *SmartContract) Invoke(APIstub shim.ChaincodeStubInterface) sc.Response {

	function, args := APIstub.GetFunctionAndParameters()

	logger.Infof("Function name is:  %d", function)
	logger.Infof("Args length is : %d", len(args))

	if function == "queryBag" {
		return s.queryBag(APIstub, args)
	} else if function == "initLedger" {
		return s.initLedger(APIstub)
	} else if function == "createBag" {
		return s.createBag(APIstub, args)
	} else if function == "createScreening" {
		return s.createScreening(APIstub, args)
	} else if function == "createRequestScreening" {
		return s.createRequestScreening(APIstub, args)
	} else if function == "createOrganization" {
		return s.createOrganization(APIstub, args)
	} else if function == "createUser" {
		return s.createUser(APIstub, args)
	} else if function == "logMovement" {
		return s.logMovement(APIstub, args)
	} else if function == "logDestroyed" {
		return s.logDestroyed(APIstub, args)
	} else if function == "queryAllBag" {
		return s.queryAllBag(APIstub)
	} else if function == "changeBagOwner" {
		return s.changeBagOwner(APIstub, args)
	} else if function == "getHistoryForBag" {
		return s.getHistoryForBag(APIstub, args)
	} else if function == "restictedMethod" {
		return s.restictedMethod(APIstub, args)
	} else if function == "readScreening" {
		return s.readScreening(APIstub, args)
	} else if function == "readScreeningDetails" {
		return s.readScreeningDetails(APIstub, args)
	}

	return shim.Error("Invalid Smart Contract function name.")
}

///core functions//
func (s *SmartContract) initLedger(APIstub shim.ChaincodeStubInterface) sc.Response {
	bags := []Bag{
		Bag{Id: "1", Generated_Date: "2020-05-12 00:00:00", Status: "Empty", Assigned_bloodBank: "32"},
		Bag{Id: "2", Generated_Date: "2020-05-12 00:00:00", Status: "Empty", Assigned_bloodBank: "1"},
	}
	i := 0
	for i < len(bags) {
		bagAsBytes, _ := json.Marshal(bags[i])
		APIstub.PutState("BAG"+strconv.Itoa(i), bagAsBytes)
		i = i + 1
	}

	return shim.Success(nil)
}

func (s *SmartContract) restictedMethod(APIstub shim.ChaincodeStubInterface, args []string) sc.Response {

	// get an ID for the client which is guaranteed to be unique within the MSP
	//id, err := cid.GetID(APIstub) -

	// get the MSP ID of the client's identity
	//mspid, err := cid.GetMSPID(APIstub) -

	// get the value of the attribute
	//val, ok, err := cid.GetAttributeValue(APIstub, "attr1") -

	// get the X509 certificate of the client, or nil if the client's identity was not based on an X509 certificate
	//cert, err := cid.GetX509Certificate(APIstub) -

	val, ok, err := cid.GetAttributeValue(APIstub, "role")
	if err != nil {
		// There was an error trying to retrieve the attribute
		shim.Error("Error while retriving attributes")
	}
	if !ok {
		// The client identity does not possess the attribute
		shim.Error("Client identity doesnot posses the attribute")
	}
	// Do something with the value of 'val'
	if val != "approver" {
		fmt.Println("Attribute role: " + val)
		return shim.Error("Only user with role as APPROVER have access this method!")
	} else {
		if len(args) != 1 {
			return shim.Error("Incorrect number of arguments. Expecting 1")
		}

		bagAsBytes, _ := APIstub.GetState(args[0])
		return shim.Success(bagAsBytes)
	}

}

///bag funtions//

func (s *SmartContract) queryBag(APIstub shim.ChaincodeStubInterface, args []string) sc.Response {

	if len(args) != 1 {
		return shim.Error("Incorrect number of arguments. Expecting 1")
	}

	bagAsBytes, _ := APIstub.GetState(args[0])
	return shim.Success(bagAsBytes)
}

func (s *SmartContract) queryAllBag(APIstub shim.ChaincodeStubInterface) sc.Response {

	startKey := "0"
	endKey := "9999999"

	resultsIterator, err := APIstub.GetStateByRange(startKey, endKey)
	if err != nil {
		return shim.Error(err.Error())
	}
	defer resultsIterator.Close()

	// buffer is a JSON array containing QueryResults
	var buffer bytes.Buffer
	buffer.WriteString("[")

	bArrayMemberAlreadyWritten := false
	for resultsIterator.HasNext() {
		queryResponse, err := resultsIterator.Next()
		if err != nil {
			return shim.Error(err.Error())
		}
		// Add a comma before array members, suppress it for the first array member
		if bArrayMemberAlreadyWritten == true {
			buffer.WriteString(",")
		}
		buffer.WriteString("{\"Key\":")
		buffer.WriteString("\"")
		buffer.WriteString(queryResponse.Key)
		buffer.WriteString("\"")

		buffer.WriteString(", \"Record\":")
		// Record is a JSON object, so we write as-is
		buffer.WriteString(string(queryResponse.Value))
		buffer.WriteString("}")
		bArrayMemberAlreadyWritten = true
	}
	buffer.WriteString("]")

	fmt.Printf("- queryAllBag:\n%s\n", buffer.String())

	return shim.Success(buffer.Bytes())
}

func (s *SmartContract) readScreening(APIstub shim.ChaincodeStubInterface, args []string) sc.Response {

	if len(args) != 1 {
		return shim.Error("Incorrect number of arguments. Expecting 1")
	}

	bagAsBytes, _ := APIstub.GetPrivateData("collectionBags", args[0])
	return shim.Success(bagAsBytes)
}

func (s *SmartContract) readScreeningDetails(APIstub shim.ChaincodeStubInterface, args []string) sc.Response {

	if len(args) != 1 {
		return shim.Error("Incorrect number of arguments. Expecting 1")
	}

	bagAsBytes, err := APIstub.GetPrivateData("collectionBagsScreeningDetails", args[0])

	if err != nil {
		jsonResp := "{\"Error\":\"Failed to get private details for " + args[0] + ": " + err.Error() + "\"}"
		return shim.Error(jsonResp)
	} else if bagAsBytes == nil {
		jsonResp := "{\"Error\":\"Marble private details does not exist: " + args[0] + "\"}"
		return shim.Error(jsonResp)
	}
	return shim.Success(bagAsBytes)
}

// create functions //

func (s *SmartContract) createScreening(APIstub shim.ChaincodeStubInterface, args []string) sc.Response {

	if len(args) != 14 {
		return shim.Error("Incorrect number of arguments. Expecting 14")
	}

	var bag = Screening{Id: args[1], Test_date: args[2], HBV: args[3], HCV: args[4], HIV: args[5], Syphilis: args[6], Malaria: args[7], PVC: args[8], BloodType: args[9], Rhesus: args[10], Approved_by: args[11], Agent_Id: args[12], Bag_id: args[13]}

	bagAsBytes, _ := json.Marshal(bag)
	APIstub.PutState(args[0], bagAsBytes)

	return shim.Success(bagAsBytes)

}

func (s *SmartContract) createRequestScreening(APIstub shim.ChaincodeStubInterface, args []string) sc.Response {

	if len(args) != 7 {
		return shim.Error("Incorrect number of arguments. Expecting 7")
	}

	var bag = RequestScreening{Id: args[1], Screener_id: args[2], Donated_date: args[3], Donor_id: args[4], Status: args[5], Bag_id: args[6]}

	bagAsBytes, _ := json.Marshal(bag)
	APIstub.PutState(args[0], bagAsBytes)

	return shim.Success(bagAsBytes)

}

func (s *SmartContract) createUser(APIstub shim.ChaincodeStubInterface, args []string) sc.Response {

	if len(args) != 9 {
		return shim.Error("Incorrect number of arguments. Expecting 9")
	}

	var bag = User{Id: args[1], FirstName: args[2], LastName: args[3], Phone: args[4], Email: args[5], Privilege: args[6], Status: args[7], Organizations_id: args[8]}

	bagAsBytes, _ := json.Marshal(bag)
	APIstub.PutState(args[0], bagAsBytes)

	return shim.Success(bagAsBytes)

}

func (s *SmartContract) createOrganization(APIstub shim.ChaincodeStubInterface, args []string) sc.Response {

	if len(args) != 13 {
		return shim.Error("Incorrect number of arguments. Expecting 13")
	}

	var bag = Organizations{Id: args[1], OrganizationsName: args[2], Phone: args[3], Email: args[4], Address: args[5], City: args[6], Latitude: args[7], Longitude: args[8], Category: args[9], Status: args[10], State: args[11], Country: args[12]}

	bagAsBytes, _ := json.Marshal(bag)
	APIstub.PutState(args[0], bagAsBytes)

	return shim.Success(bagAsBytes)

}

func (s *SmartContract) createBag(APIstub shim.ChaincodeStubInterface, args []string) sc.Response {

	if len(args) != 5 {
		return shim.Error("Incorrect number of arguments. Expecting 5")
	}

	var bag = Bag{Id: args[1], Generated_Date: args[2], Status: args[3], Assigned_bloodBank: args[4]}

	bagAsBytes, _ := json.Marshal(bag)
	APIstub.PutState(args[0], bagAsBytes)

	return shim.Success(bagAsBytes)
}

func (s *SmartContract) logMovement(APIstub shim.ChaincodeStubInterface, args []string) sc.Response {

	if len(args) != 8 {
		return shim.Error("Incorrect number of arguments. Expecting 8")
	}

	var bag = Movement{Id: args[1], Start_location: args[2], End_location: args[3], Start_time: args[4], End_time: args[5], Temperature: args[6], Bag_id: args[7]}

	bagAsBytes, _ := json.Marshal(bag)
	APIstub.PutState(args[0], bagAsBytes)

	return shim.Success(bagAsBytes)

}

func (s *SmartContract) logDestroyed(APIstub shim.ChaincodeStubInterface, args []string) sc.Response {

	if len(args) != 6 {
		return shim.Error("Incorrect number of arguments. Expecting 6")
	}

	var bag = Destroyed{Id: args[1], DestoryDate: args[2], Reason: args[3], Proof: args[4], Bag_id: args[5]}

	bagAsBytes, _ := json.Marshal(bag)
	APIstub.PutState(args[0], bagAsBytes)

	return shim.Success(bagAsBytes)

}

//others

func (t *SmartContract) getHistoryForBag(stub shim.ChaincodeStubInterface, args []string) sc.Response {

	if len(args) < 1 {
		return shim.Error("Incorrect number of arguments. Expecting 1")
	}

	bagId := args[0]

	resultsIterator, err := stub.GetHistoryForKey(bagId)
	if err != nil {
		return shim.Error(err.Error())
	}
	defer resultsIterator.Close()

	// buffer is a JSON array containing historic values for the marble
	var buffer bytes.Buffer
	buffer.WriteString("[")

	bArrayMemberAlreadyWritten := false
	for resultsIterator.HasNext() {
		response, err := resultsIterator.Next()
		if err != nil {
			return shim.Error(err.Error())
		}
		// Add a comma before array members, suppress it for the first array member
		if bArrayMemberAlreadyWritten == true {
			buffer.WriteString(",")
		}
		buffer.WriteString("{\"TxId\":")
		buffer.WriteString("\"")
		buffer.WriteString(response.TxId)
		buffer.WriteString("\"")

		buffer.WriteString(", \"Value\":")
		// if it was a delete operation on given key, then we need to set the
		//corresponding value null. Else, we will write the response.Value
		//as-is (as the Value itself a JSON marble)
		if response.IsDelete {
			buffer.WriteString("null")
		} else {
			buffer.WriteString(string(response.Value))
		}

		buffer.WriteString(", \"Timestamp\":")
		buffer.WriteString("\"")
		buffer.WriteString(time.Unix(response.Timestamp.Seconds, int64(response.Timestamp.Nanos)).String())
		buffer.WriteString("\"")

		buffer.WriteString(", \"IsDelete\":")
		buffer.WriteString("\"")
		buffer.WriteString(strconv.FormatBool(response.IsDelete))
		buffer.WriteString("\"")

		buffer.WriteString("}")
		bArrayMemberAlreadyWritten = true
	}
	buffer.WriteString("]")

	fmt.Printf("- getHistoryForAsset returning:\n%s\n", buffer.String())

	return shim.Success(buffer.Bytes())
}

func (s *SmartContract) changeBagOwner(APIstub shim.ChaincodeStubInterface, args []string) sc.Response {

	if len(args) != 2 {
		return shim.Error("Incorrect number of arguments. Expecting 2")
	}

	bagAsBytes, _ := APIstub.GetState(args[0])
	bag := Bag{}

	json.Unmarshal(bagAsBytes, &bag)
	bag.Assigned_bloodBank = args[1]

	bagAsBytes, _ = json.Marshal(bag)
	APIstub.PutState(args[0], bagAsBytes)

	return shim.Success(bagAsBytes)
}

// The main function is only relevant in unit test mode. Only included here for completeness.
func main() {

	// Create a new Smart Contract
	err := shim.Start(new(SmartContract))
	if err != nil {
		fmt.Printf("Error creating new Smart Contract: %s", err)
	}
}
