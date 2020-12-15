export CORE_PEER_TLS_ENABLED=true
export ORDERER_CA=${PWD}/artifacts/channel/crypto-config/ordererOrganizations/smartbagng.com/orderers/orderer.smartbagng.com/msp/tlscacerts/tlsca.smartbagng.com-cert.pem
export PEER0_ORG1_CA=${PWD}/artifacts/channel/crypto-config/peerOrganizations/org1.smartbagng.com/peers/peer0.org1.smartbagng.com/tls/ca.crt
export PEER0_ORG2_CA=${PWD}/artifacts/channel/crypto-config/peerOrganizations/org2.smartbagng.com/peers/peer0.org2.smartbagng.com/tls/ca.crt
export FABRIC_CFG_PATH=${PWD}/artifacts/channel/config/

export CHANNEL_NAME=mychannel

setGlobalsForOrderer() {
    export CORE_PEER_LOCALMSPID="OrdererMSP"
    export CORE_PEER_TLS_ROOTCERT_FILE=${PWD}/artifacts/channel/crypto-config/ordererOrganizations/smartbagng.com/orderers/orderer.smartbagng.com/msp/tlscacerts/tlsca.smartbagng.com-cert.pem
    export CORE_PEER_MSPCONFIGPATH=${PWD}/artifacts/channel/crypto-config/ordererOrganizations/smartbagng.com/users/Admin@smartbagng.com/msp

}

setGlobalsForPeer0Org1() {
    export CORE_PEER_LOCALMSPID="Org1MSP"
    export CORE_PEER_TLS_ROOTCERT_FILE=$PEER0_ORG1_CA
    export CORE_PEER_MSPCONFIGPATH=${PWD}/artifacts/channel/crypto-config/peerOrganizations/org1.smartbagng.com/users/Admin@org1.smartbagng.com/msp
    export CORE_PEER_ADDRESS=localhost:7051
}

setGlobalsForPeer1Org1() {
    export CORE_PEER_LOCALMSPID="Org1MSP"
    export CORE_PEER_TLS_ROOTCERT_FILE=$PEER0_ORG1_CA
    export CORE_PEER_MSPCONFIGPATH=${PWD}/artifacts/channel/crypto-config/peerOrganizations/org1.smartbagng.com/users/Admin@org1.smartbagng.com/msp
    export CORE_PEER_ADDRESS=localhost:8051

}

setGlobalsForPeer0Org2() {
    export CORE_PEER_LOCALMSPID="Org2MSP"
    export CORE_PEER_TLS_ROOTCERT_FILE=$PEER0_ORG2_CA
    export CORE_PEER_MSPCONFIGPATH=${PWD}/artifacts/channel/crypto-config/peerOrganizations/org2.smartbagng.com/users/Admin@org2.smartbagng.com/msp
    export CORE_PEER_ADDRESS=localhost:9051

}

setGlobalsForPeer1Org2() {
    export CORE_PEER_LOCALMSPID="Org2MSP"
    export CORE_PEER_TLS_ROOTCERT_FILE=$PEER0_ORG2_CA
    export CORE_PEER_MSPCONFIGPATH=${PWD}/artifacts/channel/crypto-config/peerOrganizations/org2.smartbagng.com/users/Admin@org2.smartbagng.com/msp
    export CORE_PEER_ADDRESS=localhost:10051

}


invokeFunctions() {
    # Get Transaction By tx id
    peer chaincode invoke \
        -o localhost:7050 \
        --cafile $ORDERER_CA \
        --tls $CORE_PEER_TLS_ENABLED \
        --peerAddresses localhost:7051 --tlsRootCertFiles $PEER0_ORG1_CA \
        -C mychannel -n qscc \
        -c '{"function":"GetTransactionByID","Args":["mychannel", "313e9f73e4ed64b85a0339d19dd918feab13a64548b257e412926621a90f36b5"]}'

    peer chaincode invoke \
        -o localhost:7050 \
        --cafile $ORDERER_CA \
        --tls $CORE_PEER_TLS_ENABLED \
        --peerAddresses localhost:7051 --tlsRootCertFiles $PEER0_ORG1_CA \
        -C mychannel -n qscc \
        -c '{"function":"GetChainInfo","Args":["mychannel"]}'

    peer chaincode invoke \
        -o localhost:7050 \
        --cafile $ORDERER_CA \
        --tls $CORE_PEER_TLS_ENABLED \
        --peerAddresses localhost:7051 --tlsRootCertFiles $PEER0_ORG1_CA \
        -C mychannel -n qscc \
        -c '{"function":"GetBlockByNumber","Args":["mychannel","2"]}'

}
